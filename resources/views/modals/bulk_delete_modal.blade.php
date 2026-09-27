<div class="modal fade confirmDeleteModal" id="bulk-delete-modal" tabindex="-1"
    aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-corner-8px">
            <div class="d-flex justify-content-end py-15px px-15px">
                <button type="button" class="border-0 bg-transparent" data-dismiss="modal">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                        viewBox="0 0 49.814 49.814">
                        <path id="Path_45225" data-name="Path 45225"
                            d="M239.647-672.186,238-673.833l23.26-23.26L238-720.353,239.647-722l23.26,23.26L286.167-722l1.647,1.647-23.26,23.26,10.358,10.358,12.9,12.9-1.647,1.647-23.26-23.26Z"
                            transform="translate(-238 722)" fill="#6c757d" />
                    </svg>

                </button>
            </div>
            <div class="modal-body pt-3 pb-30px px-3 px-lg-5">
                <div class="text-center pb-4">
                    <span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="63.894" height="72"
                            viewBox="0 0 63.894 72">
                            <path id="Path_45227" data-name="Path 45227"
                                d="M223.205-704a6.177,6.177,0,0,1-4.514-1.923,6.177,6.177,0,0,1-1.923-4.514V-770.4H212v-2.583h17.166V-776h29.563v2.98h17.166v2.623h-4.768v59.988a6.166,6.166,0,0,1-1.893,4.517A6.2,6.2,0,0,1,264.689-704Zm45.3-66.4H219.391v59.96a3.611,3.611,0,0,0,1.132,2.742,3.769,3.769,0,0,0,2.682,1.073h41.483a3.646,3.646,0,0,0,2.623-1.192,3.646,3.646,0,0,0,1.192-2.622Zm-33.616,53.94h2.623V-760.8h-2.623Zm15.5,0h2.623V-760.8h-2.623ZM219.391-770.4v0Z"
                                transform="translate(-212 776)" fill="#f0426b" />
                        </svg>

                    </span>
                    <h5 class="m-0 text-uppercase text-dark fs-24 fw-700 mt-4 pt-2">Confirmation</h5>
                </div>
                <div>
                    <p class="fs-14 fw-200 text-dark text-center">{{ translate('Are you sure to delete those?') }}</p>
                </div>
            </div>
            <!--Button-->
            <div class="d-flex align-items-center justify-content-between w-100 px-3 px-lg-5 pb-5 mb-3">
                <button type="button" id="back-btn"
                    class="bg-transparent border-2 border-gray-400 fs-14 fw-700 rounded-2 py-15px text-success d-block mr-2 w-100"
                    data-dismiss="modal">{{translate('No')}}</button>
                <a onclick="bulk_delete()" id="proceed-btn" href="javascript:void(0)"
                    class="bg-transparent text-center border border-2 border-gray-400 rounded-2 fs-14 fw-700 py-15px text-danger d-block w-100">{{translate('Yes')}}</a>
            </div>
        </div>
    </div>
</div>