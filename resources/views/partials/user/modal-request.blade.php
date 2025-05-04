<div class="modal fade" id="requestModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Request</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="post" action="{{ route('request.store') }}" id="form-request">
                    @csrf
                    <div class="mb-3">
                        <label for="select-request-type">Select your request type</label>
                        <select class="form-select" aria-label="Default select example" id="select-request-type"
                            name="type">
                            <option selected>Open this select menu</option>
                            <option value="1">Type 1</option>
                            <option value="2">Type 2</option>
                            <option value="3">Type 3</option>
                        </select>
                    </div>
                    <div class="form-floating">
                        <textarea name="content" class="form-control" placeholder="Write us your request" id="textarea-request" required></textarea>
                        <label for="textarea-request">Write us your request</label>
                    </div>
                    <br>
                    <div class="d-flex gap-3 justify-content-between">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Send Request</button>
                    </div>
                </form>
            </div>
            {{-- <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button>
            </div> --}}
        </div>
    </div>
</div>
