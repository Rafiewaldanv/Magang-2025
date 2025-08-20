<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Force-refresh when user returns via Back/Forward (reload once to avoid cached stale page) -->
    <script>
<!-- Reload-on-back: only for /soal routes -->
<script>
(function () {
  'use strict';

  // Only run on /soal paths
  function shouldHandle() {
    return location.pathname.startsWith('/soal');
  }
  if (!shouldHandle()) return;

  const KEY = 'bf_reload_once:' + location.pathname + location.search;
  const DEBUG = false;
  function log(...args){ if (DEBUG) console.log('[bf-reload]', ...args); }

  function alreadyReloaded(){ try { return sessionStorage.getItem(KEY) === 'reloaded'; } catch(e){ return false; } }
  function markReloaded(){ try { sessionStorage.setItem(KEY, 'reloaded'); } catch(e){} }
  function clearMark(){ try { sessionStorage.removeItem(KEY); } catch(e){} }

  function isBackNavigation(e){
    try {
      if (e && e.persisted) { log('pageshow.persisted'); return true; }
      if (performance && performance.getEntriesByType) {
        const nav = performance.getEntriesByType('navigation')[0];
        if (nav && nav.type === 'back_forward') { log('nav.type back_forward'); return true; }
      }
      if (performance && performance.navigation) {
        if (performance.navigation.type === 2) { log('legacy nav.type 2'); return true; }
      }
    } catch(err){ log('isBackNavigation err', err); }
    return false;
  }

  function handleBackNavigation(e){
    if (alreadyReloaded()) { clearMark(); log('already reloaded, clear marker'); return; }
    if (isBackNavigation(e)) {
      markReloaded();
      log('back detected -> reload');
      setTimeout(function(){ location.reload(); }, 60);
    }
  }

  window.addEventListener('pageshow', handleBackNavigation, false);

  window.addEventListener('popstate', function(e){
    log('popstate fired');
    setTimeout(function(){
      if (!alreadyReloaded()){
        markReloaded();
        log('popstate fallback -> reload');
        setTimeout(function(){ location.reload(); }, 60);
      }
    }, 40);
  }, false);

  // clear marker when user intentionally navigates away via link (so next back still reloads)
  document.addEventListener('click', function(e){
    const a = e.target.closest && e.target.closest('a');
    if (!a) return;
    const href = a.getAttribute('href');
    if (!href || href.startsWith('#') || a.target === '_blank') return;
    clearMark();
  }, true);

})();
</script>

<script>
(function () {
  function handlePageshow(e) {
    try {
      // Detect BFCache or navigation type back_forward
      var navType = null;
      if (performance && performance.getEntriesByType) {
        var navEntries = performance.getEntriesByType('navigation');
        if (Array.isArray(navEntries) && navEntries.length) navType = navEntries[0].type;
      }
      // fallback for older API
      if (navType === null && performance && performance.navigation) {
        navType = performance.navigation.type === 2 ? 'back_forward' : 'navigate';
      }

      var isBack = (e && e.persisted) || (navType === 'back_forward');

      if (!isBack) return;

      // marker unik per URL agar reload hanya terjadi sekali
      var key = 'bf_reload_' + location.pathname + location.search;
      var val = sessionStorage.getItem(key);

      if (val === 'reloaded') {
        // sudah di-reload sekali — hapus marker dan jangan reload lagi
        sessionStorage.removeItem(key);
        return;
      }

      // tandai lalu reload sekali (force reload; note: boolean arg deprecated but harmless)
      sessionStorage.setItem(key, 'reloaded');
      // use location.reload() — browsers will perform a fresh request
      location.reload();
    } catch (err) {
      // fail-safe: jangan ganggu UX kalau ada error
      console.warn('pageshow reload handler error', err);
    }
  }

  // pageshow fires on normal load and when page is restored from BFCache
  window.addEventListener('pageshow', handlePageshow);
})();
</script>

    @include('template/_head')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/style.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @yield('css-extra')
</head>
{{-- <body class="bg-light" oncontextmenu="return false"> --}}
<body class="bg-light">
    @include('template/_navbar')
    @yield('content')
    @include('template/_js')
    @include('template/admin/_js')
    @yield('js-extra')
</body>
</html>
