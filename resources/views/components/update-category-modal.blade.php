<dialog id="updateCategoryModal" class="modal">
    <div class="modal-box">
        <h3 class="text-lg font-bold">Update Category</h3>
        <form id="modalForm" action="" method="post">
            @csrf
            @method('PUT')
            <fieldset class="fieldset">
                <legend class="fieldset-legend">Name</legend>
                <input id="modalInputName" name="name" type="text" class="input w-full" placeholder="Type here" />
                <div id="modalErrorName" class="text-red-500 text-xs">

                </div>
            </fieldset>
            <fieldset class="fieldset">
                <legend class="fieldset-legend">Pick a file</legend>
                <input name="icon" type="file"
                    onchange=" document.getElementById('modalPreview').src = window.URL.createObjectURL(this.files[0])"
                    class="file-input w-full" />
                <div id="modalErrorIcon" class="text-red-500 text-xs"></div>
                <label class="label">Max size 1MB</label>
                <div class="border max-w-24 min-h-24 max-h-24 mb-2">
                    <img id="modalPreview" class="w-full h-full object-cover object-center" id="preview" src=""
                        alt="preview">
                </div>
            </fieldset>
            <button type="submit" class="btn btn-primary btn-outline ms-auto">Submit</button>
        </form>
    </div>
    <form method="dialog" class="modal-backdrop">
        <button>close</button>
    </form>
</dialog>