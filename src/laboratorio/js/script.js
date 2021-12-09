document.getElementById('modalExamResults').addEventListener('show.bs.modal', function (e) {
    const id = e.relatedTarget.getAttribute('data-bs-src');

    this.querySelector('input[name="exam_id"]').value = id;

    this.querySelectorAll('[role="exam_id"]').forEach(function(examIdspan) {
        examIdspan.innerHTML = id;
    });
});