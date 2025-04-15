<script>
    document.addEventListener('DOMContentLoaded', () => {
        const token = document.querySelector('meta[name="csrf-token"]').content;
        const post = (url, data = {}) => fetch(url, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': token,
                'X-Requested-With': 'XMLHttpRequest',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(data)
        });
        const del = url => fetch(url, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': token,
                'X-Requested-With': 'XMLHttpRequest'
            }
        });
    
        const convId = {{ $id }};
        const userMap = @json($allUsers->pluck('participantDetails.name', 'id'));
    
        const memList = document.querySelector('#memberList');
        const addList = document.querySelector('#addList');
        const addSearch = document.querySelector('#addSearch');
    
        async function renderMembers() {
            const res = await fetch(`/chat/${convId}/participants`);
            const map = await res.json();
            const ids = Object.keys(map).map(Number);
    
            memList.innerHTML = ids.map(id => `
                <li data-id="${id}" class="flex justify-between text-sm">
                    <span>${map[id]}</span>
                    <button class="rm-btn text-red-500 text-xs">remove</button>
                </li>
            `).join('');
    
            addList.innerHTML = Object.entries(userMap)
                .filter(([uid]) => !ids.includes(Number(uid)))
                .map(([uid, name]) => `
                    <li data-id="${uid}" class="flex justify-between text-sm">
                        <span>${name}</span>
                        <button class="add-btn text-blue-500 text-xs">add</button>
                    </li>
                `).join('');
    
            bindMemberButtons();
        }
    
        function bindMemberButtons() {
            memList.querySelectorAll('.rm-btn').forEach(btn => {
                btn.onclick = async e => {
                    e.preventDefault();
                    const id = e.target.closest('li').dataset.id;
                    await del(`/chat/${convId}/member/${id}`);
                    renderMembers();
                };
            });
    
            addList.querySelectorAll('.add-btn').forEach(btn => {
                btn.onclick = async e => {
                    e.preventDefault();
                    const id = e.target.closest('li').dataset.id;
                    await post(`/chat/${convId}/member`, { user_id: id });
                    renderMembers();
                };
            });
        }
    
        addSearch.addEventListener('input', e => {
            const q = e.target.value.toLowerCase();
            addList.querySelectorAll('li').forEach(li => {
                li.style.display = li.textContent.toLowerCase().includes(q) ? '' : 'none';
            });
        });
    
        renderMembers();
    });
    </script>
    