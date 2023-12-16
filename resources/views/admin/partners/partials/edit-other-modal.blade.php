<div id="modal-form-other" class="modal fade" tabindex="-1" aria-hidden="true" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="blue bigger">Izmenite podatke</h4>
            </div>

            <div class="modal-body">
                <div class="row">
                    <div class="vspace"></div>
                    <legend class="col-md-11">Maksimalni prečnik za:</legend>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label">Putničko vozilo:</label>

                            <select name="maxrim_auto" id="maxrim_auto" class="form-control">
                                <option value="-">Izaberite veličinu</option>
                                <option value="14" {!!$partner->about->car_max_rim==14? 'selected':''!!}>14"</option>
                                <option value="15" {!!$partner->about->car_max_rim==15? 'selected':''!!}>15"</option>
                                <option value="16" {!!$partner->about->car_max_rim==16? 'selected':''!!}>16"</option>
                                <option value="17" {!!$partner->about->car_max_rim==17? 'selected':''!!}>17"</option>
                                <option value="18" {!!$partner->about->car_max_rim==18? 'selected':''!!}>18"</option>
                                <option value="19" {!!$partner->about->car_max_rim==19? 'selected':''!!}>19"</option>
                                <option value="20" {!!$partner->about->car_max_rim==20? 'selected':''!!}>20"</option>
                                <option value="21" {!!$partner->about->car_max_rim==21? 'selected':''!!}>21"</option>
                                <option value="22" {!!$partner->about->car_max_rim==22? 'selected':''!!}>22"</option>
                                <option value="23" {!!$partner->about->car_max_rim==23? 'selected':''!!}>23"</option>
                                <option value="24" {!!$partner->about->car_max_rim==24? 'selected':''!!}>24"</option>
                                <option value="25" {!!$partner->about->car_max_rim==25? 'selected':''!!}>25"</option>
                                <option value="26" {!!$partner->about->car_max_rim==26? 'selected':''!!}>26"</option>
                                <option value="27" {!!$partner->about->car_max_rim==27? 'selected':''!!}>27"</option>
                                <option value="28" {!!$partner->about->car_max_rim==28? 'selected':''!!}>28"</option>
                            </select>

                        </div>
                        <div class="form-group">
                            <label class="control-label">Terensko vozilo:</label>


                            <select name="maxrim_4x4" id="maxrim_4x4" class="form-control">
                                <option value="-">Izaberite veličinu</option>
                                <option value="15" {!!$partner->about->suv_max_rim==15? 'selected':''!!}>15"</option>
                                <option value="16" {!!$partner->about->suv_max_rim==16? 'selected':''!!}>16"</option>
                                <option value="17" {!!$partner->about->suv_max_rim==17? 'selected':''!!}>17"</option>
                                <option value="18" {!!$partner->about->suv_max_rim==18? 'selected':''!!}>18"</option>
                                <option value="19" {!!$partner->about->suv_max_rim==19? 'selected':''!!}>19"</option>
                                <option value="20" {!!$partner->about->suv_max_rim==20? 'selected':''!!}>20"</option>
                                <option value="21" {!!$partner->about->suv_max_rim==21? 'selected':''!!}>21"</option>
                                <option value="22" {!!$partner->about->suv_max_rim==22? 'selected':''!!}>22"</option>
                                <option value="23" {!!$partner->about->suv_max_rim==23? 'selected':''!!}>23"</option>
                                <option value="24" {!!$partner->about->suv_max_rim==24? 'selected':''!!}>24"</option>
                                <option value="25" {!!$partner->about->suv_max_rim==25? 'selected':''!!}>25"</option>
                                <option value="26" {!!$partner->about->suv_max_rim==26? 'selected':''!!}>26"</option>
                                <option value="27" {!!$partner->about->suv_max_rim==27? 'selected':''!!}>27"</option>
                                <option value="28" {!!$partner->about->suv_max_rim==28? 'selected':''!!}>28"</option>
                            </select>

                        </div>
                        <div class="form-group">
                            <label class="control-label">Dostavno vozilo:</label>

                            <select name="maxrim_kombi" id="maxrim_kombi" class="form-control">
                                <option value="-">Izaberite veličinu</option>
                                <option value="15" {!!$partner->about->van_max_rim==15? 'selected':''!!}>do 16"</option>
                                <option value="16" {!!$partner->about->van_max_rim==16? 'selected':''!!}>16" OBRUČ</option>
                                <option value="17.5" {!!$partner->about->van_max_rim==17.5? 'selected':''!!}>17,5"</option>
                            </select>

                        </div>
                        <input type="hidden" id="form-field-other-partner_id" value="{!!$partner->partner_id!!}" name="partner_id">

                    </div>
                    <div class="col-md-5">
                        <div class="form-group">
                            <label class="control-label">Motocikl:</label>

                            <select name="maxrim_motor" id="maxrim_motor" class="form-control">
                                <option value="-">Izaberite veličinu</option>
                                <option value="15" {!!$partner->about->bike_max_rim==15? 'selected':''!!}>15"</option>
                                <option value="16" {!!$partner->about->bike_max_rim==16? 'selected':''!!}>16"</option>
                                <option value="17" {!!$partner->about->bike_max_rim==17? 'selected':''!!}>17"</option>
                                <option value="18" {!!$partner->about->bike_max_rim==18? 'selected':''!!}>18"</option>
                                <option value="19" {!!$partner->about->bike_max_rim==19? 'selected':''!!}>19"</option>
                                <option value="20" {!!$partner->about->bike_max_rim==20? 'selected':''!!}>20"</option>
                                <option value="21" {!!$partner->about->bike_max_rim==21? 'selected':''!!}>21"</option>
                                <option value="22" {!!$partner->about->bike_max_rim==22? 'selected':''!!}>22"</option>
                                <option value="23" {!!$partner->about->bike_max_rim==23? 'selected':''!!}>23"</option>
                            </select>

                        </div>
                        <div class="form-group">
                            <label class="control-label">Kamion:</label>

                            <select name="maxrim_kamion" id="maxrim_kamion" class="form-control">
                                <option value="-">Izaberite veličinu</option>
                                <option {!!$partner->about->truck_max_rim==17.5? 'selected':''!!} value="17.5">17,5"</option>
                                <option {!!$partner->about->truck_max_rim==19.5? 'selected':''!!} value="19.5">19,5"</option>
                                <option {!!$partner->about->truck_max_rim==20? 'selected':''!!} 	value="20">20"</option>
                                <option {!!$partner->about->truck_max_rim==22.5? 'selected':''!!} value="22.5">22,5"</option>
                                <option {!!$partner->about->truck_max_rim==24? 'selected':''!!} 	value="24">24"</option>
                            </select>

                        </div>
                    </div>
                </div>
                <!--
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label class="checkbox" for="form-field-mobile-service">
								<span>Mobilno montažni servis:</span>
								<input type="checkbox" id="form-field-mobile-service" name="form-field-mobile-service" {!!$partner->mobile_service_radius > 0 ? 'checked' : '' !!}>
							</label>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label class="control-label">Najveća udaljenost za mobilni servis:</label>
							<div class="controls"><input type="text" value="{!!$partner->mobile_service_radius!!}"> km</div>
						</div>
					</div>
				</div>
				-->
            </div>

            <div class="modal-footer">
                <button class="btn btn-small" data-dismiss="modal">
                    <i class="icon-remove"></i>
                    Otkaži
                </button>

                <button class="btn btn-small btn-primary btn-save-modal-other">
                    <i class="icon-ok"></i>
                    Sačuvaj
                </button>
            </div>
        </div>
    </div>

</div>