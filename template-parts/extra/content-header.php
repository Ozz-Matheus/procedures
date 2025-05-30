                <div class="margin-bottom-40">
                    <!-- tutorial -->
                    <div id="tutorial"
                        class="vc_row wpb_row vc_row-fluid image-with-text vc_column-gap-default vc_row-o-equal-height vc_row-o-content-middle vc_row-flex ts-row-wide">
                        <div class="wpb_column vc_column_container vc_col-sm-6 vc_custom_1664937069006">
                            <div class="wpb_wrapper">

                                <div class="wpb_text_column wpb_content_element">
                                    <div class="wpb_wrapper">
                                        <div class="ts-video-2 ">
                                            <a href="#" onclick="play()">
                                                <img loading="lazy" decoding="async" width="604" height="380"
                                                    src="/wp-content/uploads/2024/07/CONOCE-LOS-REQUISITOS.jpg"
                                                    class="attachment-full size-full" alt=""
                                                    srcset="/wp-content/uploads/2024/07/CONOCE-LOS-REQUISITOS.jpg 604w, /wp-content/uploads/2024/07/CONOCE-LOS-REQUISITOS-300x189.jpg 300w, /wp-content/uploads/2024/07/CONOCE-LOS-REQUISITOS-600x377.jpg 600w, /wp-content/uploads/2024/07/CONOCE-LOS-REQUISITOS-310x195.jpg 310w"
                                                    sizes="(max-width: 604px) 100vw, 604px">
                                            </a>
                                            <div class="ts-popup-modal ts-video-modal">
                                                <div class="overlay" onclick="stop()"></div>
                                                <div class="video-container popup-container box-flex">
                                                    <span class="close" onclick="stop()">Close </span>
                                                    <div class="video-content">
                                                        <div class="ts-video pho" style="width:800px; height:450px;">

                                                            <video id="videoPho" controls="" width="800" height="450" name="media"
                                                                preload="metadata">
                                                                <source
                                                                    src="https://thulio.app/wp-content/uploads/2024/06/video-de-como-unirme.mp4"
                                                                    type="video/mp4">
                                                                    Your browser does not support the video tag.
                                                            </video>
                                                        </div>
                                                        <script>
                                                            var myVideo = document.getElementById("videoOzz");
                                                            function play() {
                                                                myVideo.play();
                                                            }
                                                            function stop() {
                                                                myVideo.pause();
                                                            }
                                                        </script>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>


                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="wpb_column vc_column_container vc_col-sm-6 vc_custom_1664937075753">
                            <div class="wpb_wrapper">
                                <h3 style="text-align: left" class="vc_custom_heading vc_do_custom_heading title">Tutorial para<br>
                                    unirme al club</h3>
                                <div class="wpb_text_column wpb_content_element unete-box-app">
                                    <div class="wpb_wrapper">
                                        <p>Necesitas ser mayor de edad, <strong>INE, CURP y RFC</strong>*</p>
                                        <p>Turistas:<br>
                                            <strong>Pasaporte , Official Government ID, TAX ID</strong>*.
                                        </p>
                                        <p><strong>*Opcional</strong></p>
                                        <p><strong>Si tienes dudas visita nuestro Centro de Inscripción en :</strong></p>
                                        <p><a class="product-name" href="https://maps.app.goo.gl/X2jmfNQ5CF55VQ8Z8" target="_blank"
                                                rel="noopener"><strong>Jalapa 234. Colonia Roma</strong><strong>Sur. Thulio
                                                    Coffeshop.</strong></a></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                     <!-- tutorial -->
                    <hr>
                    <div class="margin-bottom-40">
                        <form method="post" action="" class="margin-bottom-40">
                            <input type="hidden" name="user_email" id="user_email" value="<?php echo $current_user->user_email; ?>" required><br>
                            <input type="hidden" name="user_name" id="user_name" value="<?php echo $current_user->first_name . ' ' . $current_user->last_name; ?>" required><br>


                            <label for="social" class="margin-bottom-20"><strong>RED SOCIAL FAVORITA :</strong></label>
                            <select class="margin-bottom-40" id="social" name="social" required>
                                <option value="">--Selecciona una opción--</option>
                                <option value="facebook">Facebook</option>
                                <option value="instagram">Instagram</option>
                                <option value="tiktok">TikTok</option>
                                <option value="x">X</option>
                            </select>

                            <label for="perfil" class="margin-bottom-20"><strong>NOMBRE DEL PERFIL DE TU RED SOCIAL FAVORITA :</strong></label>
                            <input class="margin-bottom-40" type="text" id="perfil" name="perfil" placeholder="Tu Nombre De Usuario En  Redes" required>

                            <label for="telefono" class="margin-bottom-20"><strong>TELÉFONO CON WHATSAPP:</strong></label>
                            <input class="margin-bottom-40" type="tel"  name="telefono" id="telefono" value="" required>

                            <input type="submit" name="new_procedure" value="Siguiente">
                        </form>
                    </div>
                </div>
