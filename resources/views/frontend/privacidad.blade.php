@extends('layouts.master')

@section('metadescription') Política de privacidad y política de cookies - apartamentosierranevada.net @endsection
@section('title')  Política de privacidad y política de cookies - apartamentosierranevada.net @endsection

@section('content')
<style type="text/css">
  #primary-menu ul li  a{
    color: #3F51B5!important;
  }
  #primary-menu ul li  a div{
    text-align: left!important;
  }
  #content p {
    line-height: 1.2;
  }
  .fa-circle{
    font-size: 10px!important;
  }
  #contact-form input{
    color: black!important;
  }
  *::-webkit-input-placeholder {
    /* Google Chrome y Safari */
    color: rgba(0,0,0,0.85) !important;
  }
  *:-moz-placeholder {
    /* Firefox anterior a 19 */
    color: rgba(0,0,0,0.85) !important;
  }
  *::-moz-placeholder {
    /* Firefox 19 y superior */
    color: rgba(0,0,0,0.85) !important;
  }
  *:-ms-input-placeholder {
    /* Internet Explorer 10 y superior */
    color: rgba(0,0,0,0.85) !important;
  }
  @media (max-width: 768px){


    .container-mobile{
      padding: 0!important
    }
    #primary-menu{
      padding: 40px 15px 0 15px;
    }
    #primary-menu-trigger {
      color: #3F51B5!important;
      top: 5px!important;
      left: 5px!important;
      border: 2px solid #3F51B5!important;
    }
    .container-image-box img{
      height: 180px!important;
    }

    #content-form-book {
      padding: 0px 0 40px 0
    }
    .daterangepicker {
      top: 135%!important;
    }
    .img{
      max-height: 530px;
    }
    .button.button-desc.button-3d{
      background-color: #4cb53f!important;
    }

  }

</style>
<section id="content" style="margin-top: 15px">

  <div class="container container-mobile clearfix push-0">
    <div class="row">
      <h1 class="center psuh-20">Política de privacidad y política de cookies</h2>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 center" >
    
<p style="text-align:left"><span style="font-family:Times New Roman,serif"><span style="font-size:medium"><span style="color:#0000ff"><u><a href="{{ url('/') }}" style="color:#0000ff" target="_blank"><span style="color:#3f51b5"><span style="font-family:Arial,serif">apartamentosierranevada.net</span></span></a></u></span><span style="color:#000000"><span style="font-family:Arial,serif">&nbsp;es un sitio web titularidad de </span></span><span style="color:#000000"><span style="font-family:Arial,serif"><strong>Intermediaci&oacute;n y gesti&oacute;n Inmobiliaria Sierra Nevada</strong></span></span><span style="color:#000000"> </span><span style="color:#000000"><span style="font-family:Arial,serif"><strong>SL</strong></span></span><span style="color:#000000"><span style="font-family:Arial,serif"> (IN-GEST SIERRA NEVADA S.L )<br />
<br />
El usuario accede a este sitio web de forma voluntaria. El acceso a este sitio web tiene car&aacute;cter gratuito. La navegaci&oacute;n en este sitio web implica aceptar y conocer las advertencias legales, condiciones y t&eacute;rminos de uso y pol&iacute;tica de privacidad contenidos en &eacute;l. Si el usuario no est&aacute; de acuerdo con estas condiciones de uso y no presta su consentimiento, no podr&aacute; hacer uso de este sitio web.<br />
<br />
El acceso a este sitio web, as&iacute; como el uso que pueda hacerse de la informaci&oacute;n que contiene, son de la exclusiva responsabilidad del usuario. El usuario se compromete a hacer uso del mismo de acuerdo a la finalidad del sitio web<br />
<br />
El usuario no puede hacer un uso il&iacute;cito de la informaci&oacute;n contenida en esta web, ni llevar a cabo acciones que puedan da&ntilde;ar o alterar los sistemas inform&aacute;ticos de esta web.<br />
<br />
Queda prohibida la inclusi&oacute;n y comunicaci&oacute;n de contenidos, por parte de los usuarios, que sean falsos o inexactos y que induzcan o puedan inducirnos a error o a otros usuarios o terceros. El usuario ser&aacute; el &uacute;nico responsable de los perjuicios que cause mediante la comunicaci&oacute;n de dichos datos.<br />
<br />
Queda prohibido el uso de datos personales de terceros sin su consentimiento, as&iacute; como el uso de datos identificativos de terceros con el &aacute;nimo de hacerse pasar por o fingir ser cualquier otra persona o entidad.<br />
<br />
La propiedad realiza los m&aacute;ximos esfuerzos para evitar errores en los contenidos que se publican en el sitio web, reserv&aacute;ndose la facultad de poder modificarlos en cualquier momento.<br />
<br />
IN-GEST SIERRA NEVADA S.L declina expresamente cualquier responsabilidad por error u omisi&oacute;n en los contenidos de este sitio web y de los da&ntilde;os y perjuicios que puedan deberse a la falta de veracidad, exactitud y actualidad de los mismos.</span></span><br />
<br />
<span style="color:#0000ff"><u><a href="{{ url('/') }}" style="color:#0000ff" target="_blank"><span style="color:#3f51b5"><span style="font-family:Arial,serif">www.apartamentosierranevada.net</span></span></a></u></span><span style="color:#000000"><span style="font-family:Arial,serif">&nbsp;puede ofrecer enlaces a otros sitios web o facilitar su acceso mediante buscadores ubicados en su sitio web.<br />
<br />
IN-GEST SIERRA NEVADA S.L no asume ninguna responsabilidad en relaci&oacute;n con estos sitios enlazados, ni los resultados de las b&uacute;squedas, ya que no tiene ning&uacute;n tipo de control sobre ellos, su contenido, productos y servicios ofrecidos, etc.<br />
<br />
La finalidad de estos servicios es informar al usuario de otras fuentes de informaci&oacute;n, por lo que el usuario accede bajo su exclusiva responsabilidad al contenido y en las condiciones de uso que rijan en los mismos.<br />
<br />
IN-GEST SIERRA NEVADA S.L no se responsabilizar&aacute; de ninguna consecuencia, da&ntilde;o o perjuicio que pudieran derivarse del uso de este sitio web o de sus contenidos, incluidos da&ntilde;os inform&aacute;ticos y la introducci&oacute;n de virus IN-GEST SIERRA NEVADA S.L no garantiza la ausencia de virus ni de otros elementos en el sitio web, introducidos por terceros ajenos, que puedan producir alteraciones en los sistemas f&iacute;sicos o l&oacute;gicos de los usuarios, pero utiliza todos los medios a su alcance para que esto no suceda.<br />
<br />
IN-GEST SIERRA NEVADA S.L se reserva el derecho de modificar o borrar en cualquier momento, sin previo aviso y/o justificaci&oacute;n, el dise&ntilde;o, la configuraci&oacute;n y la informaci&oacute;n contenida en este sitio web, si as&iacute; lo estima oportuno.<br />
<br />
IN-GEST SIERRA NEVADA S.L no se hace responsable de los perjuicios que estas modificaciones puedan causar. No obstante utilizar&aacute; todos los recursos que tenga a su alcance para informar a los usuarios de dichas modificaciones.</span></span></span></span></p>

<h3 style="text-align:left"><span style="font-size:13pt"><span style="font-family:Times New Roman,serif"><span style="color:#444444"><span style="font-family:Arial,serif"><span style="font-size:x-large">2. Registros y suscripciones</span></span></span></span></span></h3>

<p style="text-align:left"><span style="font-family:Times New Roman,serif"><span style="font-size:medium"><span style="color:#000000"><span style="font-family:Arial,serif">El acceso a este sitio web no implica la obligaci&oacute;n de facilitar datos personales. No obstante, algunos de los servicios ofrecidos, como la propia reserva de alojamientos, requieren que el usuario facilite una serie de datos personales.<br />
<br />
El usuario se compromete a aportar datos veraces, exactos y completos, tanto en el momento del registro, como en posteriores comunicaciones y se hace responsable de comunicar cualquier modificaci&oacute;n en los mismos.<br />
<br />
La no cumplimentaci&oacute;n de campos indicados como obligatorios, que aparecen en los formularios de reserva y contacto, podr&aacute; tener como consecuencia que IN-GEST SIERRA NEVADA S.L. no pueda atender la solicitud del usuario.<br />
<br />
El uso de este sitio web implica la aceptaci&oacute;n de las condiciones generales y pol&iacute;tica de privacidad de IN-GEST SIERRA NEVADA S.L<br />
<br />
Los usuarios podr&aacute;n solicitar la suscripci&oacute;n a boletines informativos y de ofertas (&ldquo;newsletter&rdquo;) de forma voluntaria. Los usuarios podr&aacute;n solicitar la baja de dichos servicios en el momento que lo deseen dirigiendo un escrito a IN-GEST SIERRA NEVADA S.L. o utilizando cualquier otro medio que la empresa facilite a tal efecto, como por ejemplo el mecanismo denominado &ldquo;Darse de baja&rdquo;, ubicado en la parte final de los propios boletines informativos.</span></span></span></span></p>

<h3 style="text-align:left"><span style="font-size:13pt"><span style="font-family:Times New Roman,serif"><span style="color:#444444"><span style="font-family:Arial,serif"><span style="font-size:x-large">3. Propiedad intelectual e industrial</span></span></span></span></span></h3>

<p style="text-align:left"><span style="font-family:Times New Roman,serif"><span style="font-size:medium"><span style="color:#000000"><span style="font-family:Arial,serif">Todo el contenido gr&aacute;fico y la informaci&oacute;n de la p&aacute;gina web&nbsp;</span></span><span style="color:#0000ff"><u><a href="{{ url('/') }}" style="color:#0000ff" target="_blank"><span style="color:#3f51b5"><span style="font-family:Arial,serif">www.apartamentosierranevada.net</span></span></a></u></span><span style="color:#000000"><span style="font-family:Arial,serif">, as&iacute; como el dise&ntilde;o gr&aacute;fico, las im&aacute;genes, las bases de datos y los programas son propiedad exclusiva IN-GEST SIERRA NEVADA S.L la cual se reserva todos los derechos de explotaci&oacute;n.<br />
<br />
En ning&uacute;n caso el acceso o navegaci&oacute;n en&nbsp;</span></span><span style="color:#0000ff"><u><a href="{{ url('/') }}" style="color:#0000ff" target="_blank"><span style="color:#3f51b5"><span style="font-family:Arial,serif">www.apartamentosierranevada.net</span></span></a></u></span><span style="color:#000000"><span style="font-family:Arial,serif">&nbsp;implica renuncia, transmisi&oacute;n o licencia total o parcial de IN-GEST SIERRA NEVADA S.L para uso personal al usuario sobre sus derechos de propiedad intelectual e industrial.<br />
<br />
La reproducci&oacute;n, distribuci&oacute;n, comercializaci&oacute;n o transformaci&oacute;n no autorizadas de estas obras constituye una infracci&oacute;n de los derechos de propiedad intelectual de IN-GEST SIERRA NEVADA S.L<br />
<br />
El usuario se compromete a no realizar ninguna acci&oacute;n que perjudique la titularidad de este sitio. La utilizaci&oacute;n no autorizada de la informaci&oacute;n contenida en este sitio web, as&iacute; como los perjuicios ocasionados en los derechos de propiedad intelectual e industrial de sus titulares, pueden dar lugar al ejercicio de las acciones que legalmente correspondan y, si procede, a las responsabilidades que de dicho ejercicio se deriven.</span></span></span></span></p>

<h3 style="text-align:left"><span style="font-size:13pt"><span style="font-family:Times New Roman,serif"><span style="color:#444444"><span style="font-family:Arial,serif"><span style="font-size:x-large">4. Modificaciones</span></span></span></span></span></h3>

<p style="text-align:left"><span style="font-family:Times New Roman,serif"><span style="font-size:medium"><span style="color:#000000"><span style="font-family:Arial,serif">Estas Condiciones podr&aacute;n sufrir modificaciones cuando IN-GEST SIERRA NEVADA S.L lo considere oportuno, bien con la finalidad de adecuarse a los cambios legislativos, bien con el fin de llevar a cabo otro tipo de mejoras. Estas modificaciones ser&aacute;n v&aacute;lidas desde su publicaci&oacute;n en este sitio web.</span></span></span></span></p>

<p style="text-align:left"><br />
<span style="font-family:Times New Roman,serif"><span style="font-size:medium"><span style="color:#000000"><span style="font-family:Arial,serif">IN-GEST SIERRA NEVADA S.L utilizar&aacute; todos los medios a su alcance para dar a conocer a los usuarios de la web los cambios realizados.</span></span></span></span></p>

<h3 style="text-align:left"><span style="font-size:13pt"><span style="font-family:Times New Roman,serif"><span style="color:#444444"><span style="font-family:Arial,serif"><span style="font-size:x-large">5. Jurisdicci&oacute;n</span></span></span></span></span></h3>

<p style="text-align:left"><span style="font-family:Times New Roman,serif"><span style="font-size:medium"><span style="color:#000000"><span style="font-family:Arial,serif">Las partes se someten, con renuncia a cualquier otro fuero, a los juzgados y tribunales del domicilio de IN-GEST SIERRA NEVADA S.L </span></span></span></span></p>

<h3 style="text-align:left"><span style="font-size:13pt"><span style="font-family:Times New Roman,serif"><span style="color:#444444"><span style="font-family:Arial,serif"><span style="font-size:x-large">6. Pol&iacute;tica de Privacidad - Protecci&oacute;n de datos de car&aacute;cter personal</span></span></span></span></span></h3>

<p style="text-align:left"><span style="font-family:Times New Roman,serif"><span style="font-size:medium"><span style="color:#000000"><span style="font-family:Arial,serif">En cumplimiento con la Ley Org&aacute;nica 15/1999, de 13 de diciembre, sobre Protecci&oacute;n de Datos de Car&aacute;cter Personal (en adelante &quot;LOPD&quot;) y su normativa de desarrollo, se informa a los usuarios que los datos de car&aacute;cter personal que faciliten mediante correo electr&oacute;nico, enviados a trav&eacute;s de formularios web, &aacute;reas privadas o por cualquier otro medio ubicado en este sitio web, como la contrataci&oacute;n del alojamiento, los servicios adicionales y a lo largo de su relaci&oacute;n con la empresa, ser&aacute;n incorporados a ficheros titularidad de IN-GEST SIERRA NEVADA S.L. (Responsable del Fichero), con la finalidad de atender a las consultas y solicitudes recibidas, contactar con usted, gestionar los servicios solicitados, las relaciones comerciales y las funciones leg&iacute;timas propias de su actividad, as&iacute; como para el env&iacute;o de los boletines y noticias a los que se haya podido suscribir y para el env&iacute;o de futuras comunicaciones comerciales que pudieran ser de su inter&eacute;s.<br />
<br />
El tratamiento de sus datos personales se realizar&aacute; de manera confidencial. IN-GEST SIERRA NEVADA S.L est&aacute; comprometida con la privacidad y la protecci&oacute;n de los datos personales de los usuarios y utiliza todos los recursos a su alcance para garantizar la seguridad y privacidad de los interesados.<br />
<br />
IN-GEST SIERRA NEVADA S.L cumple con la legislaci&oacute;n vigente en Protecci&oacute;n de Datos de Car&aacute;cter Personal, figura inscrita en el Registro General de la Agencia de Protecci&oacute;n de Datos y act&uacute;a siguiendo las recomendaciones, informes, innovaciones y directrices que ofrecen los diferentes organismos de protecci&oacute;n de datos, auton&oacute;micos, estatales y europeos y otras autoridades de control.<br />
<br />
En su oferta de servicios de alojamiento IN-GEST SIERRA NEVADA S.L pone a disposici&oacute;n de sus clientes una serie de servicios opcionales, caracter&iacute;sticos de cada destino, para intentar hacer su estancia lo m&aacute;s agradable posible. Para prestar estos servicios la empresa puede contar con personal propio o con colaboradores externos.<br />
<br />
El cliente que contrata los servicios da su consentimiento expreso a la empresa para que pueda comunicar sus datos (identificativos, de contacto y relativos a los alojamientos y servicios contratados) a las empresas y personas colaboradoras de IN-GEST SIERRA NEVADA S.L S&oacute;lo aquellos empleados y colaboradores de IN-GEST SIERRA NEVADA S.L que necesiten conocer tales datos en orden a la eficaz prestaci&oacute;n de cada uno de los servicios solicitados por Vd. podr&aacute;n tener acceso a los mismos.</span></span></span></span></p>

<h3 style="text-align:left"><span style="font-size:13pt"><span style="font-family:Times New Roman,serif"><span style="color:#444444"><span style="font-family:Arial,serif"><span style="font-size:x-large">6.1. Recogida de datos de car&aacute;cter personal</span></span></span></span></span></h3>

<p style="text-align:left"><span style="font-family:Times New Roman,serif"><span style="font-size:medium"><span style="color:#000000"><span style="font-family:Arial,serif">IN-GEST SIERRA NEVADA S.L recoger&aacute; los datos de car&aacute;cter personal de manera adecuada, pertinente seg&uacute;n las finalidades, utilidades, servicios y/o prestaciones incluidas en su web, de forma, adem&aacute;s, determinada, expl&iacute;cita y leg&iacute;tima, por lo que en ning&uacute;n caso se emplear&aacute;n medios fraudulentos, desleales o, naturalmente, il&iacute;citos o que en alguna forma pongan en peligro los leg&iacute;timos derechos de los visitantes.<br />
<br />
El usuario aporta sus datos personales de forma libre y voluntaria. IN-GEST SIERRA NEVADA S.L no solicita m&aacute;s informaci&oacute;n personal que la que es necesaria para recibir el servicio solicitado. El env&iacute;o de informaci&oacute;n a trav&eacute;s de esta web conlleva necesariamente la autorizaci&oacute;n expresa para los tratamientos de datos descritos en esta pol&iacute;tica de privacidad y en las condiciones generales del sitio web.</span></span></span></span></p>

<h3 style="text-align:left"><span style="font-size:13pt"><span style="font-family:Times New Roman,serif"><span style="color:#444444"><span style="font-family:Arial,serif"><span style="font-size:x-large">6.2. Derechos de los interesados</span></span></span></span></span></h3>

<p style="text-align:left"><span style="font-family:Times New Roman,serif"><span style="font-size:medium"><span style="color:#000000"><span style="font-family:Arial,serif">Puede ejercer los derechos de acceso, rectificaci&oacute;n, cancelaci&oacute;n y oposici&oacute;n dirigi&eacute;ndose a IN-GEST SIERRA NEVADA S.L por correo electr&oacute;nico&nbsp;</span></span><span style="color:#0000ff"><u><a href="mailto:reservas@apartamentosierranevada.net" style="color:#0000ff" target="_blank"><span style="color:#3f51b5"><span style="font-family:Arial,serif">reservas@apartamentosierranevada.net</span></span></a></u></span><span style="color:#000000"><span style="font-family:Arial,serif">&nbsp;Las solicitudes deber&aacute;n adjuntar copia de documento acreditativo de la identidad del solicitante.</span></span></span></span></p>

<h4 style="text-align:left"><span style="font-family:Times New Roman,serif"><span style="color:#444444"><span style="font-family:Arial,serif"><span style="font-size:x-large">6.3. Medidas de seguridad</span></span></span></span></h4>

<p style="text-align:left"><span style="font-family:Times New Roman,serif"><span style="font-size:medium"><span style="color:#000000"><span style="font-family:Arial,serif">IN-GEST SIERRA NEVADA S.L tratar&aacute; los datos de car&aacute;cter personal contenidos en sus ficheros adoptando las medidas de &iacute;ndole t&eacute;cnica y organizativa que sean necesarias para garantizar su seguridad y evitar su alteraci&oacute;n, p&eacute;rdida, tratamiento o acceso no autorizado, habida cuenta del estado de la tecnolog&iacute;a, la naturaleza de los datos almacenados y los riesgos a que est&aacute;n expuestos, ya provengan de la acci&oacute;n humana o del medio f&iacute;sico o natural, de acuerdo con la legislaci&oacute;n vigente que resulte de aplicaci&oacute;n en materia de medidas de seguridad de los ficheros que contengan datos de car&aacute;cter personal, particularmente por la LOPD y su normativa de desarrollo.<br />
<br />
Sin embargo, los usuarios son informados de que las medidas de seguridad inform&aacute;tica no son inexpugnables y no se est&aacute; a salvo de posibles intromisiones ilegales e indebidas, que no ser&iacute;an responsabilidad de ISDE S.L.</span></span></span></span></p>

<h4 style="text-align:left"><span style="font-family:Times New Roman,serif"><span style="color:#444444"><span style="font-family:Arial,serif"><span style="font-size:x-large">6.4. Datos de Tr&aacute;fico y Cookies</span></span></span></span></h4>

<p style="text-align:left"><span style="font-family:Times New Roman,serif"><span style="font-size:medium"><span style="color:#000000"><span style="font-family:Arial,serif">Para la utilizaci&oacute;n de nuestro sitio web es necesario la utilizaci&oacute;n de cookies, es decir, peque&ntilde;os ficheros de datos que se generan en el ordenador del usuario y que permiten el correcto funcionamiento de la web (selecci&oacute;n de idioma, par&aacute;metros de b&uacute;squedas, etc.) y que desaparecen al terminar la conexi&oacute;n. Si lo deseas puedes configurar tu navegador para ser avisado en pantalla de la recepci&oacute;n de cookies y para impedir la instalaci&oacute;n de cookies en tu disco duro.<br />
<br />
Por favor, consulta las instrucciones y manuales de tu navegador para ampliar esta informaci&oacute;n. La informaci&oacute;n obtenida es totalmente an&oacute;nima y, en ning&uacute;n caso, puede ser asociada a un usuario concreto e identificado.<br />
<br />
Asimismo, y con la finalidad de ofrecer un mejor servicio a trav&eacute;s de este sitio,&nbsp;</span></span><span style="color:#0000ff"><u><a href="{{ url('/') }}" style="color:#0000ff" target="_blank"><span style="color:#3f51b5"><span style="font-family:Arial,serif">www.apartamentosierranevada.net</span></span></a></u></span><span style="color:#000000"><span style="font-family:Arial,serif">&nbsp;registra la direcci&oacute;n IP, permiti&eacute;ndose as&iacute; el posterior procesamiento de los datos con el fin de analizar el n&uacute;mero de p&aacute;ginas visitadas, el n&uacute;mero de visitas, as&iacute; como la actividad de los visitantes de la web, y su frecuencia de utilizaci&oacute;n.<br />
<br />
Con la finalidad ya mencionada, este sitio est&aacute; siendo medido y analizado con Google Analytics y/o herramientas similares, que pueden utilizar marcas en las p&aacute;ginas y cookies para analizar lo que sucede en las diferentes p&aacute;ginas del sitio web de IN-GEST SIERRA NEVADA S.L.</span></span></span></span></p>

<h4 style="text-align:left"><span style="font-family:Times New Roman,serif"><span style="color:#444444"><span style="font-family:Arial,serif"><span style="font-size:x-large">6.5. Vigencia</span></span></span></span></h4>

<p style="text-align:left"><span style="font-family:Times New Roman,serif"><span style="font-size:medium"><span style="color:#000000"><span style="font-family:Arial,serif">IN-GEST SIERRA NEVADA S.L </span></span><span style="color:#000000">se reserva el derecho de modificar su pol&iacute;tica de privacidad de acuerdo a su criterio, a cambios legislativos o jurisprudenciales.<br />
<br />
Si </span><span style="color:#000000"><span style="font-family:Arial,serif">IN-GEST SIERRA NEVADA S.L</span></span><span style="color:#000000">. introdujera alguna modificaci&oacute;n, el nuevo texto ser&aacute; publicado en este mismo sitio web, donde el usuario podr&aacute; tener conocimiento de la misma.<br />
<br />
En cualquier caso, la relaci&oacute;n con los usuarios se regir&aacute; por las normas previstas en el momento preciso en que se accede al sitio web. Es por ello que </span><span style="color:#000000"><span style="font-family:Arial,serif">IN-GEST SIERRA NEVADA S.L </span></span><span style="color:#000000">le recomienda visitar esta pol&iacute;tica de privacidad de forma peri&oacute;dica.</span><span style="color:#000000"> </span></span></span></p>

<p style="text-align:left"><br />
&nbsp;</p>

<p>&nbsp;</p>

</section>

@endsection
@section('scripts')

@endsection