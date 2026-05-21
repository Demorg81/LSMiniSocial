document.addEventListener('DOMContentLoaded', function () {

    // ── LIKES ────────────────────────────────────────────────────────────────
    document.querySelectorAll('.like-btn').forEach(function (btn) {
        btn.addEventListener('click', async function () {
            const postId = btn.dataset.postId;
            const liked  = btn.dataset.liked === '1';
            const method = liked ? 'DELETE' : 'POST';

            btn.disabled = true;

            try {
                const response = await fetch('/posts/' + postId + '/like', {
                    method: method,
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                });

                if (!response.ok) throw new Error('Request failed');

                const data = await response.json();

                const countEl = document.getElementById('like-count-' + postId);
                if (countEl) countEl.textContent = data.like_count;

                if (liked) {
                    btn.dataset.liked = '0';
                    btn.classList.remove('liked', 'btn-outline-danger');
                    btn.classList.add('btn-outline-light');
                    btn.textContent = '♡ Like';
                } else {
                    btn.dataset.liked = '1';
                    btn.classList.add('liked', 'btn-outline-danger');
                    btn.classList.remove('btn-outline-light');
                    btn.textContent = '♥ Unlike';
                }
            } catch (err) {
                console.error('Like error:', err);
            } finally {
                btn.disabled = false;
            }
        });
    });

    // ── TOGGLE DE COMENTARIOS ──────────────────────────────────────────────────────
    document.querySelectorAll('.comments-toggle-btn').forEach(function (btn) {
        btn.addEventListener('click', async function () {
            const postId  = btn.dataset.postId;
            const section = document.getElementById('comments-section-' + postId);

            if (section.classList.contains('d-none')) {
                section.classList.remove('d-none');
                await loadComments(postId);
            } else {
                section.classList.add('d-none');
            }
        });
    });

    // ── CARGAR COMENTARIOS ────────────────────────────────────────────────────────
    async function loadComments(postId) {
        const list = document.getElementById('comments-list-' + postId);
        list.innerHTML = '<p class="text-secondary small">Loading...</p>';

        try {
            const response = await fetch('/posts/' + postId + '/comments', {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            });

            if (!response.ok) throw new Error('Request failed');

            const data = await response.json();
            renderComments(postId, data.data ?? []);
        } catch (err) {
            list.innerHTML = '<p class="text-secondary small">Could not load comments.</p>';
            console.error('Load comments error:', err);
        }
    }

    // ── RENDERIZAR COMENTARIOS ──────────────────────────────────────────────────────
    function renderComments(postId, comments) {
        const list = document.getElementById('comments-list-' + postId);

        if (comments.length === 0) {
            list.innerHTML = '<p class="text-secondary small">No comments yet. Be the first!</p>';
            return;
        }

        list.innerHTML = comments.map(function (c) {
            // currentUserId is injected by home.php
            const canDelete = (parseInt(c.user_id) === currentUserId || parseInt(c.is_owner) === 1)
                ? `<button
                        class="btn btn-link btn-sm text-danger p-0 ms-2 delete-comment-btn"
                        data-comment-id="${c.id}"
                        data-post-id="${postId}"
                   >Delete</button>`
                : '';

            return `
                <div class="border-bottom pb-2 mb-2" id="comment-${c.id}">
                    <strong class="small">${escHtml(c.username)}</strong>
                    <span class="text-secondary small ms-2">${escHtml(c.created_at)}</span>
                    ${canDelete}
                    <p class="mb-0 small mt-1">${escHtml(c.content)}</p>
                </div>`;
        }).join('');

        list.querySelectorAll('.delete-comment-btn').forEach(function (btn) {
            btn.addEventListener('click', function () {
                deleteComment(btn.dataset.commentId, btn.dataset.postId);
            });
        });
    }

    // ── PUBLICAR COMENTARIOS ─────────────────────────────────────────────────────────
    document.querySelectorAll('.comment-form').forEach(function (form) {
        form.addEventListener('submit', async function (e) {
            e.preventDefault();

            const postId    = form.dataset.postId;
            const input     = form.querySelector('.comment-input');
            const content   = input.value.trim();
            const submitBtn = form.querySelector('button[type="submit"]');

            if (!content) return;

            submitBtn.disabled = true;

            try {
                const response = await fetch('/posts/' + postId + '/comments', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({ content: content })
                });

                if (!response.ok) throw new Error('Request failed');

                input.value = '';
                await loadComments(postId);

                const countEl = document.getElementById('comment-count-' + postId);
                if (countEl) countEl.textContent = parseInt(countEl.textContent || '0') + 1;

            } catch (err) {
                console.error('Post comment error:', err);
            } finally {
                submitBtn.disabled = false;
            }
        });
    });

    // ── ELIMINAR COMENTARIOS ───────────────────────────────────────────────────────
    async function deleteComment(commentId, postId) {
        try {
            const response = await fetch('/comments/' + commentId, {
                method: 'DELETE',
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            });

            if (!response.ok) throw new Error('Request failed');

            await loadComments(postId);

            const countEl = document.getElementById('comment-count-' + postId);
            if (countEl) countEl.textContent = Math.max(0, parseInt(countEl.textContent || '1') - 1);

        } catch (err) {
            console.error('Delete comment error:', err);
        }
    }

    // ── UTILS ────────────────────────────────────────────────────────────────
    function escHtml(str) {
        const div = document.createElement('div');
        div.appendChild(document.createTextNode(String(str)));
        return div.innerHTML;
    }
});