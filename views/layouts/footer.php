    </div><!-- /.page-content -->
</div><!-- /.main-content -->
<script>
// Close sidebar on mobile when clicking outside
document.addEventListener('click', function(e) {
    var sidebar = document.getElementById('sidebar');
    var toggle = document.querySelector('.menu-toggle');
    if (window.innerWidth <= 768 && sidebar.classList.contains('open') &&
        !sidebar.contains(e.target) && !toggle.contains(e.target)) {
        sidebar.classList.remove('open');
    }
});
</script>
</body>
</html>
