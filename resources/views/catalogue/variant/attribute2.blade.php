<div class="modal fade bd-example-modal-lg" id="attribteModel_2" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="user_delete_form" method="GET" class="remove-record-model">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Attribute 2</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
                    <table class="table">
                        <thead>
                        <tr>
                            <th>Type</th>
                            @foreach($languages  as $language)
                                <th> {{$language['name']}}</th>

                            @endforeach
                        </tr>
                        </thead>

                        <tbody>

                        <tr>
                            <td>Attribute</td>
                            @foreach($languages  as $language)
                                <td>
                                    <input
                                        type="text"
                                        class="form-control"
                                        id="attr_2"
                                    />
                                </td>
                            @endforeach
                        </tr>

                        <tr>
                            <td>Value </td>
                            @foreach($languages  as $language)
                                <td>
				    			<span >
				    				<input
                                        type="text"
                                        class="form-control"
                                        id="value2"

                                    />
				    			</span>
                                </td>
                            @endforeach
                        </tr>

                        </tbody>


                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-dismiss="modal">Done</button>
                </div>
            </form>
        </div>
    </div>
</div>


