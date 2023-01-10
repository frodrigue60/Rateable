<div class="modal fade" id="exampleModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content customModal">
            <div class="modal-header mt-2 customModal">
                <form class="d-flex w-100" role="search">
                    <input id="searchInputModal" class="form-control" type="search" placeholder="Search"
                        aria-label="Search" autocomplete="false">
                </form>
            </div>
            <div id="modalBody" class="modal-body p-2 customModal">
                <div class="res">
                    <span class="catTitle">Anime</span>
                    <div id="posts">
                    </div>
                    <span class="catTitle">Artist</span>
                    <div id="artists">
                    </div>
                    <span class="catTitle">Tag</span>
                    <div id="tags">
                    </div>
                </div>
            </div>
            <div class="modal-footer justify-content-center customModal">
                <div class="d-flex">
                    <a href="{{ route('filter') }}" class="btn btn-primary color3">More
                        options</a>
                </div>
            </div>
        </div>
    </div>
</div>
