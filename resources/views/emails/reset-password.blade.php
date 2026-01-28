<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
</head>

<body style="font-family: Arial, Helvetica, sans-serif; color:#333;">

    <h2>Recuperación de contraseña</h2>

    <p>
        Has solicitado cambiar la contraseña de tu cuenta en el sistema
        <strong>AnaliSys</strong>.
    </p>

    <p>
        Para continuar con el proceso, haz clic en el siguiente botón:
    </p>

    <!-- BOTÓN NARANJA -->
    <p>
        <a href="{{ $link }}"
           style="background:#F37334;
                  color:#ffffff;
                  padding:8px 16px;
                  font-size:14px;
                  text-decoration:none;
                  border-radius:4px;
                  display:inline-block;">
            Cambiar contraseña
        </a>
    </p>

    <p>
        Este enlace expira en <strong>30 minutos</strong>.
    </p>

    <!-- MENSAJE DE ADVERTENCIA -->
    <p style="margin-top:18px; font-size:13px; color:#c0392b;">
        <strong>Importante:</strong>
        Si usted no solicitó este cambio de contraseña,
        puede ignorar este mensaje con total seguridad.
        No se realizará ningún cambio en su cuenta.
    </p>

    <br><br>

    <!-- ================= FIRMA / TABLA INSTITUCIONAL ================= -->
    <table style="border:0;
                  padding:2px;
                  vertical-align:middle;
                  width:700px;
                  border:1px solid #000;
                  border-collapse:collapse;">

        <tr>
            <!-- LOGO UCR -->
            <td style="width:30%; padding:10px; vertical-align:middle;">
                <a href="http://www.ucr.ac.cr/">
                    <img src="http://www.cia.ucr.ac.cr/wp-content/gallery/galeria_cia/firma-ucr.png"
                         width="180"
                         height="70"
                         alt="Universidad de Costa Rica"
                         style="display:block;">
                </a>
            </td>

            <!-- LOGO CIA -->
            <td style="width:30%; padding:10px; vertical-align:middle;">
                <a href="http://www.cia.ucr.ac.cr/">
                    <img src="http://www.cia.ucr.ac.cr/wp-content/gallery/galeria_cia/firma-cia.png"
                         width="150"
                         height="69"
                         alt="Unidad de la UCR"
                         style="display:block;">
                </a>
            </td>

            <!-- DATOS -->
            <td style="width:40%;
                       padding:10px;
                       vertical-align:middle;
                       color:white;
                       background:#00c0f3;
                       font-family:Arial, Helvetica, sans-serif;
                       font-size:13px;">
                <strong style="font-size:15px;">Sistema AnaliSys</strong><br>
                Lab. Recursos Naturales<br>
                (506) 2511-2073<br>
                <a href="mailto:info.cia@ucr.ac.cr"
                   style="color:white; text-decoration:none;">
                    info.cia@ucr.ac.cr
                </a>
            </td>
        </tr>
    </table>
    <!-- =============================================================== -->

</body>
</html>
