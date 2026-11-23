/* Practice test (interactive MCQ) — client state machine.
 * Loaded on every public page (include/_head.php). Needs jQuery and window.GRAVITY_BASE.
 * The server (home/practice_answer, home/practice_reveal, home/practice_result) decides
 * correctness, attempts, time and grades; this file only drives the screen.
 * Two display modes (data-mode): 'all' = every question on the page + Finish button,
 * 'one' = one question at a time + Next button. */
(function ($) {
	'use strict';
	var base = window.GRAVITY_BASE || '';

	// Loads the list of tests into #bodycontainer (sidebar item "Practice tests").
	window.ajaxpracticelist = function () {
		var $b = $('#bodycontainer');
		if (!$b.length) { return; }
		$('#headerhomen').css({ display: 'none' });
		$b.html('<img src="' + base + 'images/loader.gif" alt="">');
		$.post(base + 'home/practice_list', {}, function (data) {
			$b.html(data);
			$('html, body').animate({ scrollTop: Math.max(0, $b.offset().top - 90) }, 'slow');
		});
	};

	// Loads the test shell into #result6 (from the tests list or from a lesson).
	window.ajaxpractice = function (id) {
		var $t = $('#result6');
		if (!$t.length) { return; }
		$('#headerhomen').css({ display: 'none' });
		$('.pt-catalog').attr('hidden', true);
		$t.html('<img src="' + base + 'images/loader.gif" alt="">');
		$.post(base + 'home/practice', { id_practice_test: id }, function (data) {
			$t.html(data);
			var root = document.getElementById('pt-root');
			if (root) { PracticeTest.init(root); }
			$('html, body').animate({ scrollTop: Math.max(0, $t.offset().top - 90) }, 'slow');
		});
	};

	// "Back" from the result screen: the lesson content when the test was opened from a
	// lesson, otherwise the list of tests.
	window.ptBack = function (idSection) {
		if (idSection && $('#id_section').length && typeof window.ajaxdata === 'function') {
			window.ajaxdata($('#id_section').val());
			return;
		}
		var $c = $('.pt-catalog');
		if ($c.length) {
			$('#result6').empty();
			$c.removeAttr('hidden');
			$('html, body').animate({ scrollTop: Math.max(0, $c.offset().top - 90) }, 'slow');
		} else {
			window.ajaxpracticelist();
		}
	};

	function esc(s) { return $('<div>').text(s === null || s === undefined ? '' : String(s)).html(); }

	var PracticeTest = {
		init: function (root) {
			if (root.getAttribute('data-pt-init')) { return; }
			root.setAttribute('data-pt-init', '1');

			var $root = $(root),
				url = root.getAttribute('data-url'),
				testId = parseInt(root.getAttribute('data-test'), 10),
				total = parseInt(root.getAttribute('data-total'), 10),
				mode = root.getAttribute('data-mode') === 'one' ? 'one' : 'all',
				$qs = $root.find('.pt-q'),
				$doneCount = $root.find('.pt-done-count'),
				$bar = $root.find('.pt-bar i'),
				$overlay = $root.find('.pt-overlay'),
				$modalBody = $root.find('.pt-modal-body'),
				$timer = $root.find('.pt-timer'),
				$track = $timer.find('.pt-timer-track i'),
				$sr = $root.find('.pt-sr'),
				limit = parseInt(root.getAttribute('data-limit'), 10) || 0,
				deadline = limit ? Date.now() + limit * 1000 : 0,
				warnAt = Math.round(limit * 0.5),               // orange from half time
				lowAt = Math.min(60, Math.round(limit * 0.2)),  // red + pulse for the last minute (or last 20%)
				tick = null, finished = false, timedOut = false, warned = false,
				busy = false, idx = 0, done = 0, $hintQ = null;

			// ---- per-question state lives on the section element ----
			function isLocked($q) { return $q.data('ptLocked') === true; }
			function lockAll($q) {
				$q.data('ptLocked', true).addClass('is-done');
				$q.find('.pt-opt').prop('disabled', true).addClass('is-disabled');
			}
			function markDone($q) {
				if ($q.data('ptCounted')) { return; }
				$q.data('ptCounted', true);
				done++;
				$doneCount.text(done);
				$bar.css('width', Math.round(done / total * 100) + '%');
				if (mode === 'all' && done >= total) { $root.find('.pt-finish').addClass('is-ready').focus(); }
			}
			function feedback($q, html, cls) {
				$q.find('.pt-feedback').attr('class', 'pt-feedback ' + (cls || '')).html(html);
			}
			function reveal($q, n, expl) {
				$q.find('.pt-opt[data-opt="' + n + '"]').removeClass('is-disabled').addClass('is-answer');
				if (expl) { $q.find('.pt-feedback').append('<div class="pt-expl">' + expl + '</div>'); }
				$q.find('.pt-reveal').attr('hidden', true);
			}
			function showNext($q) {
				if (mode === 'one') { $q.find('.pt-next').removeAttr('hidden').focus(); }
			}
			// the question keyboard shortcuts apply to: the focused one, else the first still open one
			function current() {
				if (mode === 'one') { return $qs.eq(idx); }
				var $a = $(document.activeElement).closest('.pt-q');
				if ($a.length && !isLocked($a)) { return $a; }
				var $f = $qs.filter(function () { return !isLocked($(this)); }).first();
				return $f.length ? $f : $qs.eq(0);
			}
			function show(i) {
				idx = i;
				$qs.attr('hidden', true);
				var $q = $qs.eq(i);
				$q.removeAttr('hidden');
				$q.find('.pt-opt').first().focus();
			}

			function post(action, extra, $q, cb) {
				busy = true;
				$.ajax({
					url: url + action, type: 'POST', dataType: 'json',
					data: $.extend({ id_practice_test: testId, id_practice_question: $q.attr('data-q') }, extra)
				}).done(function (r) { busy = false; cb(r || {}); })
				  .fail(function () {
					busy = false;
					feedback($q, 'Connection problem. Please try again.', 'is-error');
					if (!isLocked($q)) { $q.find('.pt-opt').not('.is-wrong').prop('disabled', false); }
				  });
			}

			function onAnswer($q, $b, r) {
				if (r.error) {
					if (r.error === 'restart') { window.ajaxpractice(testId); return; }
					if (r.error === 'timeout') { timeUp(); return; }
					lockAll($q); markDone($q); showNext($q); return;
				}
				$b.removeClass('is-selected');
				if (r.correct) {
					$b.addClass('is-correct');
					lockAll($q);
					feedback($q, '<b>Correct!</b> Grade: <span class="pt-grade">' + esc(r.grade) + '</span>' +
						(r.explanation ? '<div class="pt-expl">' + r.explanation + '</div>' : ''), 'is-ok');
					markDone($q);
					showNext($q);
					return;
				}
				$b.addClass('is-wrong');
				if (r.attempts_left > 0) {
					$q.find('.pt-att').text(r.attempt + 1);
					$q.find('.pt-opt').not('.is-wrong').prop('disabled', false);
					feedback($q, 'Not correct. Read the hint and try again.', 'is-warn');
					openHint(r.hint, $q);
					return;
				}
				lockAll($q);
				feedback($q, '<b>No attempts left.</b> Grade: <span class="pt-grade">' + esc(r.grade) + '</span>', 'is-fail');
				markDone($q);
				if (r.correct_option) { reveal($q, r.correct_option, r.explanation); showNext($q); }
				else { $q.find('.pt-reveal').removeAttr('hidden').focus(); }
			}

			$root.on('click', '.pt-opt', function () {
				var $b = $(this), $q = $b.closest('.pt-q');
				if (busy || finished || isLocked($q) || hintOpen() || $b.prop('disabled')) { return; }
				$q.find('.pt-opt').prop('disabled', true);
				$b.addClass('is-selected');
				post('practice_answer', { option: $b.attr('data-opt') }, $q, function (r) { onAnswer($q, $b, r); });
			});

			$root.on('click', '.pt-reveal', function () {
				var $q = $(this).closest('.pt-q');
				if (busy) { return; }
				post('practice_reveal', {}, $q, function (r) {
					if (!r.error) { reveal($q, r.correct_option, r.explanation); }
					showNext($q);
				});
			});

			$root.on('click', '.pt-next', function () {
				if (busy) { return; }
				if (idx + 1 < total) { show(idx + 1); } else { finish(); }
			});

			$root.on('click', '.pt-finish', function () {
				if (busy || finished) { return; }
				var left = total - done;
				if (left > 0 && !window.confirm(left + ' question' + (left === 1 ? '' : 's') + ' not answered yet. Finish anyway?')) { return; }
				finish();
			});

			function finish() {
				if (finished) { return; }
				finished = true;
				stopTimer();
				busy = true;
				$.post(url + 'practice_result', { id_practice_test: testId }, function (html) {
					busy = false;
					$qs.attr('hidden', true);
					$root.find('.pt-timer, .pt-head, .pt-intro, .pt-finish-bar').attr('hidden', true);
					$root.find('.pt-summary').html(html).removeAttr('hidden');
					$('html, body').animate({ scrollTop: Math.max(0, $root.offset().top - 90) }, 'slow');
				}).fail(function () {
					// keep the student out of a dead end (e.g. the connection drops when the time runs out)
					busy = false;
					finished = false;
					$root.find('.pt-summary').html('<div class="pt-msg pt-msg-warn">Connection problem: your result could not be loaded. ' +
						'<button type="button" class="pt-btn pt-retry">Show my result</button></div>').removeAttr('hidden');
				});
			}
			$root.on('click', '.pt-retry', function () { finish(); });

			// ---- countdown (timed tests): sticky bar at the top that empties as time passes.
			// The server checks the limit too (home/practice_answer refuses late answers).
			function fmt(s) { s = Math.max(0, s); var m = Math.floor(s / 60), r = s % 60; return (m < 10 ? '0' : '') + m + ':' + (r < 10 ? '0' : '') + r; }
			function stopTimer() { if (tick) { clearInterval(tick); tick = null; } }
			function startTimer() {
				if (!limit) { return; }
				function upd() {
					if (!$.contains(document.documentElement, root)) { stopTimer(); return; }
					var left = Math.max(0, Math.ceil((deadline - Date.now()) / 1000));
					$timer.find('b').text(fmt(left));
					$track.css('width', (left / limit * 100) + '%');
					$timer.toggleClass('is-low', left <= lowAt);
					$timer.toggleClass('is-warn', left <= warnAt && left > lowAt);
					if (!warned && left <= 60 && limit > 60) { warned = true; $sr.text('One minute left.'); }
					if (left <= 0) { stopTimer(); timeUp(); }
				}
				upd();
				tick = setInterval(upd, 1000);
			}
			// time over: close the test where it stands, show it for a moment, then the result
			function timeUp() {
				if (finished || timedOut) { return; }
				timedOut = true;
				stopTimer();
				closeHint();
				$qs.each(function () { lockAll($(this)); });
				$root.addClass('is-timeup');
				$timer.removeClass('is-low is-warn').addClass('is-up');
				$timer.find('.pt-timer-label').text('Time is up');
				$timer.find('b').text('00:00');
				$track.css('width', '0');
				$sr.text('Time is up. The test is closed.');
				setTimeout(finish, 1500);
			}

			// ---- hint dialog (custom: the site loads no Bootstrap JS) ----
			function hintOpen() { return !$overlay.attr('hidden'); }
			function openHint(html, $q) {
				$hintQ = $q;
				$modalBody.html(html);
				$overlay.removeAttr('hidden');
				$('body').addClass('pt-no-scroll');
				$root.find('.pt-modal-close').focus();
			}
			function closeHint() {
				if (!hintOpen()) { return; }
				$overlay.attr('hidden', true);
				$('body').removeClass('pt-no-scroll');
				var $first = ($hintQ || current()).find('.pt-opt').not(':disabled').first();
				if ($first.length) { $first.focus(); }
			}
			$root.on('click', '.pt-modal-close', closeHint);
			$overlay.on('click', function (e) { if (e.target === this) { closeHint(); } });

			// ---- keyboard: 1-4 choose an option, Enter = Next / See answer / Finish, Esc closes the hint ----
			$(document).off('keydown.pt').on('keydown.pt', function (e) {
				if (!$.contains(document.documentElement, root)) { $(document).off('keydown.pt'); return; }
				if (e.which === 27 && hintOpen()) { closeHint(); return; }
				if (hintOpen() || finished) { return; }
				var tag = (document.activeElement && document.activeElement.tagName) || '';
				if (tag === 'INPUT' || tag === 'TEXTAREA' || tag === 'SELECT') { return; }
				var $q = current();
				if (e.which >= 49 && e.which <= 52 && !isLocked($q) && !busy) {
					$q.find('.pt-opt[data-opt="' + (e.which - 48) + '"]').not(':disabled').trigger('click');
				} else if (e.which === 13 && tag !== 'BUTTON') {
					var $n = $q.find('.pt-next').not('[hidden]');
					if ($n.length) { $n.trigger('click'); return; }
					var $r = $q.find('.pt-reveal').not('[hidden]');
					if ($r.length) { $r.trigger('click'); return; }
					if (mode === 'all' && done >= total) { $root.find('.pt-finish').trigger('click'); }
				}
			});

			if (mode === 'one') { show(0); } else { $qs.removeAttr('hidden'); }
			startTimer();
		}
	};
	window.PracticeTest = PracticeTest;
})(jQuery);
