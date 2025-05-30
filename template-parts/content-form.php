                <div class="margin-bottom-40">
                	<?php
				        $templateHeader = plugin_dir_path(__FILE__) . '../template-parts/extra/content-header.php';
				        if (file_exists($templateHeader)) {
				            require_once $templateHeader;
				        }
					?>
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
