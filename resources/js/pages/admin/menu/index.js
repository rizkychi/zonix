import Sortable from "sortablejs";

export function init() {
    /* ── CSRF ──────────────────────────────────────────────────────────────── */
    const CSRF = document.querySelector('meta[name="csrf-token"]').content;
    const headers = { "X-CSRF-TOKEN": CSRF, Accept: "application/json" };

    const ROUTES = {
        store: "menu",
        reorder: "menu/action/reorder",
        base: "menu",
    };

    /* ── Init ALL nested-sortable ──────────────────────────────────────────── */
    const MAX_NESTED = 3;

    function getNested(el) {
        let nested = 1;
        let parent = el.closest(".list-group-item");
        while (parent) {
            nested++;
            parent = parent.parentElement?.closest(".list-group-item");
        }
        return nested;
    }

    function initSortable(container) {
        if (!container || container._sortable) return;
        container._sortable = Sortable.create(container, {
            group: { name: "menu", pull: true, put: true },
            animation: 150,
            fallbackOnBody: true,
            swapThreshold: 0.3,
            ghostClass: "sortable-ghost",
            chosenClass: "sortable-chosen",
            dragClass: "sortable-drag",
            onMove(evt) {
                const targetParentItem = evt.to.closest(
                    '.list-group-item[data-type="title"]',
                );
                if (targetParentItem) {
                    return false;
                }

                const nested = getNested(evt.to);

                // Calculate max nested level of dragged item
                const draggedItem = evt.dragged;
                const draggedNested = getMaxChildNested(draggedItem);

                if (nested + draggedNested > MAX_NESTED) {
                    return false; // ← cancel drop
                }
            },
        });
    }

    function getMaxChildNested(item, current = 0) {
        const children = item.querySelectorAll(
            ":scope > .nested-list > .list-group-item",
        );
        if (!children.length) return current;
        let max = current;
        children.forEach((child) => {
            max = Math.max(max, getMaxChildNested(child, current + 1));
        });
        return max;
    }

    function initAllSortables() {
        document.querySelectorAll(".nested-sortable").forEach(initSortable);
    }

    initAllSortables();

    /* ── Serialize DOM → nestable JSON ────────────────────────────────────── */
    function serializeList(container) {
        return Array.from(
            container.querySelectorAll(":scope > .list-group-item[data-id]"),
        ).map((el) => {
            const obj = { id: parseInt(el.dataset.id) };
            const nested = el.querySelector(":scope > .nested-list");
            if (nested) obj.children = serializeList(nested);
            return obj;
        });
    }

    /* ── Save Order ────────────────────────────────────────────────────────── */
    document
        .getElementById("btn-save-order")
        .addEventListener("click", function () {
            const btn = this;
            window.showSpinner(btn, true);

            const order = JSON.stringify(
                serializeList(document.getElementById("root-list")),
            );

            fetch(ROUTES.reorder, {
                method: "POST",
                headers: { ...headers, "Content-Type": "application/json" },
                body: JSON.stringify({ order }),
            })
                .then(async (r) => {
                    const res = await r.json();
                    if (!r.ok) {
                        throw res;
                    }
                    return res;
                })
                .then((res) => {
                    if (res.success) {
                        localStorage.setItem("successMessage", res.message);
                        location.reload();
                    }
                })
                .catch((err) => {
                    toast.error(err.message || "Failed to save order.");
                })
                .finally(() => {
                    window.showSpinner(btn, false);
                });
        });

    /* ── Create button ─────────────────────────────────────────────────────── */
    document.addEventListener("click", function (e) {
        const createBtn = e.target.closest("#btn-create-item");
        if (!createBtn) return;

        // Reset form
        document.getElementById("edit-form").reset();
        document.getElementById("edit-icon-preview").className = "ri-question-line";
        document.getElementById("edit-active").checked = true;
        toggleEditFields("item");

        document.getElementById("modal-title-create").classList.remove("d-none");
        document.getElementById("modal-title-edit").classList.add("d-none");

        new bootstrap.Modal(document.getElementById("editModal")).show();
    });

    /* ── Edit button ───────────────────────────────────────────────────────── */
    document.addEventListener("click", function (e) {
        const editBtn = e.target.closest(".btn-edit-item");
        if (!editBtn) return;
        const id = editBtn.dataset.id;

        fetch(`${ROUTES.base}/${id}`, { headers })
            .then((r) => r.json())
            .then((item) => {
                document.getElementById("edit-id").value = item.id;
                document.getElementById("edit-title").value = item.title || "";
                document.getElementById("edit-icon").value = item.icon || "";
                document.getElementById("edit-icon-preview").className = item.icon || "ri-question-line";
                document.getElementById("edit-route").value = item.route || "";
                document.getElementById("edit-url").value = item.url || "";
                document.getElementById("edit-permission").value = item.permission || "";
                document.getElementById("edit-permission").dispatchEvent(new Event("change")); // for select2
                document.getElementById("edit-badge-text").value = item.badge_text || "";
                document.getElementById("edit-badge-class").value = item.badge_class || "";
                document.getElementById("edit-badge-class").dispatchEvent(new Event("change")); // for select2
                document.getElementById("edit-active").checked = !!item.is_active;
                document.getElementById("edit-new-tab").checked = !!item.open_new_tab;
                document.querySelector( `input[name="edit_type"][value="${item.type}"]`).checked = true;

                console.log(item.badge_class);
                
                toggleEditFields(item.type);

                document.getElementById("modal-title-create").classList.add("d-none");
                document.getElementById("modal-title-edit").classList.remove("d-none");

                new bootstrap.Modal(document.getElementById("editModal")).show();
            });
    });

    document.querySelectorAll('input[name="edit_type"]').forEach((r) => {
        r.addEventListener("change", () => toggleEditFields(r.value));
    });

    function toggleEditFields(type) {
        document.querySelectorAll(".eio").forEach((el) => {
            el.style.display = type === "item" ? "" : "none";
        });
    }

    /* ── Save button ─────────────────────────────────────────────────────────── */
    document
        .getElementById("btn-save-edit")
        .addEventListener("click", function () {
            const id = document.getElementById("edit-id").value;
            const btn = this;
            window.showSpinner(btn, true);

            const data = collectForm("#edit-form");
            data.type = document.querySelector('input[name="edit_type"]:checked').value;

            var _url = id ? `${ROUTES.base}/${id}` : `${ROUTES.base}`;
            var _method = id ? "PUT" : "POST";
            fetch(_url, {
                method: _method,
                headers: { ...headers, "Content-Type": "application/json" },
                body: JSON.stringify(data),
            })
                .then(async (r) => {
                    const res = await r.json();
                    if (!r.ok) {
                        throw res;
                    }
                    return res;
                })
                .then((res) => {
                    if (res.success) {
                        bootstrap.Modal.getInstance(document.getElementById("editModal")).hide();
                        localStorage.setItem("successMessage", res.message);
                        location.reload();
                    } else {
                        toast.error(res.message || "An error occurred.");
                    }
                })
                .catch((err) => {
                    toast.error(err.message);
                })
                .finally(() => {
                    window.showSpinner(btn, false);
                });
        });

    /* ── Toggle active ─────────────────────────────────────────────────────── */
    document.addEventListener("click", function (e) {
        const togBtn = e.target.closest(".btn-toggle-item");
        if (!togBtn) return;
        const id = togBtn.dataset.id;
        const titles = JSON.parse(togBtn.dataset.title);
        const item = document.querySelector(
            `.list-group-item[data-id="${id}"]`,
        );

        fetch(`${ROUTES.base}/${id}/toggle`, { method: "PATCH", headers })
            .then((r) => r.json())
            .then((res) => {
                if (res.success) {
                    item.classList.toggle("item-inactive", !res.is_active);
                    togBtn.innerHTML = res.is_active
                        ? '<i class="ri-eye-fill"></i>'
                        : '<i class="ri-eye-off-fill"></i>';
                    togBtn.title = titles[res.is_active ? 0 : 1];
                    item.querySelector(".is-inactive")?.classList.toggle(
                        "d-none",
                        res.is_active,
                    );
                }
            });
    });

    /* ── Delete ────────────────────────────────────────────────────────────── */
    let deleteId = null;
    document.addEventListener("click", function (e) {
        const delBtn = e.target.closest(".btn-delete-item");
        if (!delBtn) return;
        deleteId = delBtn.dataset.id;
        new bootstrap.Modal(document.getElementById("deleteModal")).show();
    });

    document
        .getElementById("btn-confirm-delete")
        .addEventListener("click", function () {
            if (!deleteId) return;
            fetch(`${ROUTES.base}/${deleteId}`, { method: "DELETE", headers })
                .then((r) => r.json())
                .then((res) => {
                    if (res.success) {
                        bootstrap.Modal.getInstance(
                            document.getElementById("deleteModal"),
                        ).hide();
                        const el = document.querySelector(
                            `.list-group-item[data-id="${deleteId}"]`,
                        );
                        if (el) el.remove();
                        deleteId = null;
                    }
                });
        });

    /* ── Helper Functions ────────────────────────────────────────────────────── */
    // Helper: collect form data into object
    function collectForm(selector) {
        const form = document.querySelector(selector);
        const data = {};
        new FormData(form).forEach((v, k) => { data[k] = v; });
        // checkbox: FormData only includes checked boxes, so we need to add unchecked ones manually
        data.is_active    = form.querySelector('[name="is_active"]')?.checked    ? 1 : 0;
        data.open_new_tab = form.querySelector('[name="open_new_tab"]')?.checked ? 1 : 0;
        return data;
    }

    // Alert: Show success message after redirect
    const successMessage = localStorage.getItem("successMessage");
    if (successMessage) {
        toast.success(successMessage);
        localStorage.removeItem("successMessage");
    }

    // Preview icon
    document.getElementById('edit-icon').addEventListener('input', function () {
        document.getElementById('edit-icon-preview').className = this.value || 'ri-question-line';
    });
}
