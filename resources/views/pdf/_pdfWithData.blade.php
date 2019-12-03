<?php

use \Carbon\Carbon; ?>
<style>
  .page-break {
    page-break-after: always;
  }
</style>
<?php for ($i = 0; $i <= 1; $i++): ?>
  <h2 style="font-weight: 800; color: red; text-align: center; font-family: 'Verdana'; font-size: 20px;">Documento Check In</h2>
  <p style="color: black; font-family: 'Verdana';margin-bottom: 0;font-size: 11px; text-align: justify;">
    <b>Nombre: <?php echo ucfirst($data['book']->customer->name) ?></b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    @if(trim($data['book']->customer->DNI) != '')
    <b>DNI: {{$data['book']->customer->DNI}} </b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    @else
    <b>DNI: _______________ </b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    @endif
    <b>Dirección: <?php echo ($data['book']->customer->address) ? $data['book']->customer->address : "_________________________"; ?></b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <b>Teléfono: <?php echo ($data['book']->customer->phone) ? $data['book']->customer->phone : "___________"; ?></b>
    <br>------------------------------------------------------------------------------------------------------<br> 
    <b>Fecha reserva: 
      <?php
      echo Carbon::createFromFormat('Y-m-d', $data['book']->start)->formatLocalized('%d %b') . " - " . Carbon::createFromFormat('Y-m-d', $data['book']->finish)->formatLocalized('%d %b') . " " . Carbon::createFromFormat('Y-m-d', $data['book']->finish)->format('Y')
      ?>
    </b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <b>Noches:</b> <?php echo Carbon::createFromFormat('Y-m-d', $data['book']->start)->diffInDays(Carbon::createFromFormat('Y-m-d', $data['book']->finish)) ?> <br>
    <b>Ocupantes:</b> <?php echo $data['book']->pax ?><br>
    <b>Apartamento: <?php echo $data['book']->room->nameRoom ?></b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <b>Nº Plaza Parking:  <?php echo $data['book']->room->parking ?></b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <b>Nº Taquilla Guarda esquíes: <?php echo $data['book']->room->locker ?></b> 
    <br>------------------------------------------------------------------------------------------------------<br> 
    <b>Total Reserva:</b> <?php echo $data['book']->total_price ?>€<br>
    <b>Cobrado:</b> <?php echo $data['pendiente'] ?>€<br>
    <b>Pendiente de abono:</b>  <?php echo $data['book']->total_price - $data['pendiente'] ?>€ 
    <br>------------------------------------------------------------------------------------------------------<br> 
  </p>
  <h2 style="font-weight: 800; color: red; text-align: center; font-family: 'Verdana'; font-size: 18px;">
    Condiciones Alquiler Apartamentos Miramar Ski
  </h2>

  <p style="color: black; font-family: 'Verdana';margin-bottom: 30px;font-size: 12px; text-align: justify;">
    <b>Hora de Entrada:</b> Desde las 17,30h a 19,00h en el caso de llegar más tarde les dejaremos las llaves en una caja de seguridad y les mandaremos instrucciones de cómo acceder a su apartamento.
<br><br>
    <b>Hora de Salida:</b> La vivienda deberá ser desocupada antes de las 11,59h a.m (de lo contrario se podrá cobrará una noche más de alquiler apartamento según tarifa apartamento y ocupación. La plaza de garaje debe quedar libre a esta hora o bien pagar la estancia de un nuevo día.
<br><br>
    <b>Fianza:</b> Según nuestras condiciones aceptadas, <b>No realizamos ningún cargo en tu tarjeta, tan solo nos has dado una  preautorización por si se produjeran desperfectos y 24 horas después de tu check out está preautorización desaparecerá.</b> Establecemos el importe máximo de la fianza en 300€ pero en el caso de que se vaya a realizar algún cargo por desperfectos siempre tendrás la opción de revisarlo y ver las pruebas aportadas.
<br><br>
    <b>Pago de la reserva:</b> El apartamento debe estar completamente abonado a la entrega de llaves. En el caso de no cumplir con lo establecido no se podrá ocupar la vivienda. 
<br><br>    
    <b>Periodo del alquiler:</b> Por el motivo que sea si la persona que alquila decide marcharse antes del periodo contratado no tiene derecho a devolución del importe de los días no disfrutados.
<br><br>    
    <b>Meteorología y estado de pistas:</b> Las condiciones del alquiler de la vivienda son completamente ajenas a las condiciones meteorológicas, al estado de las carreteras, al estado de las pistas de esquí, falta de nieve o incluso al cierre de la estación por lo que tampoco se podrá reclamar devolución por estos motivos. 
<br><br>    
    <b>Nº de personas:</b> El apartamento no podrá ser habitado por más personas de las camas que dispone. 
<br><br>    
    <b>No se admiten animales:</b> ningún tipo de animales de compañía ni mascotas. 
<br><br>    
    <b>Sabanas y Toallas están incluidas en todas las reservas.</b>
<br><br>    
    <b>Se ruega guardar silencio en los apartamentos y en las zonas comunes a partir de las 23 hrs</b>, por respeto al sueño y a la tranquilidad de los demás inquilinos y propietarios del edificio Miramarski. 
<br><br>
    <b>Checkout : El alojamiento se deberá entregar antes de las 12:00 con : </b><br><br>
    <b>Si algunos de estos requisitos no se cumplen podría conllevar la perdida de la fianza, total o parcialmente: </b>
    •	Estado de limpieza aceptable.<br>
    •	Vajilla limpia y recogida.<br>
    •	Muebles de cama en la misma posición que se entregaron.<br>
    •	<b>Sin basuras en el apartamento.</b><br>
    •	Nevera vacía (sin comida sobrante).<br>
    •	Edredones doblados en los armarios.<br>
    <br>
    <b>Devolución de llaves:</b> las puedes dejar en la cocina de tu apartamento <br>
    <b>(Cuidado: primero necesitas el mando a distancia para sacar que sacar el coche del parking) </b>
    Confirmo que se me ha informado y acepto las condiciones del alquiler de la vivienda que se detallan en este documento.<br/>

    @if(trim($data['book']->customer->accepted_hiring_policies) != '')
    <br><b style="color:red"> ESTAS CONDICIONES FUERON ACEPTADAS ON LINE POR EL CLIENTE EL <?php echo date_policies($data['book']->customer->accepted_hiring_policies); ?></b>
    @endif
  </p>
  <div style="position:relative;">
  <div style="position: absolute; bottom: 0px; left: 0px;font-size: 11px;">
    <div style="width:250px; text-align: center;">
    <?php echo ucfirst($data['book']->customer->name) ?>
    </div>
  </div>

  <div style="position: absolute; bottom: 0px; right: 0px; font-size: 11px; text-align: right;">
    <div style="width:250px; text-align: center;">
    <div>
    <img src="data:image/jpeg;base64,/9j/4AAQSkZJRgABAQAAAQABAAD/2wBDAAMCAgICAgMCAgIDAwMDBAYEBAQEBAgGBgUGCQgKCgkICQkKDA8MCgsOCwkJDRENDg8QEBEQCgwSExIQEw8QEBD/2wBDAQMDAwQDBAgEBAgQCwkLEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBD/wAARCADAAKADASIAAhEBAxEB/8QAHQABAAIDAQEBAQAAAAAAAAAAAAYHAQQFAgMICf/EADcQAAEDBAIBAgUCBAQHAQAAAAECAwQABQYRByESEzEIFCJBURVhFiMycSRSkdEXJUJGYoG18P/EABcBAQEBAQAAAAAAAAAAAAAAAAABAgP/xAAhEQEBAAICAgMAAwAAAAAAAAAAAQIRITESQQMiUWFxgf/aAAwDAQACEQMRAD8A/qnSlKBSlKBSlKBSlKBWCQkbNZr4TGUSYrsZ0kIdQWzo6OiNdf60H2BChsVmoLwpdLnd+LMckXma5MnsQxClSnAAqQ6wosreIBIBWWysgEgeWgT71OqWauqFV5y/k1+ttotuK4bJTHyjLpotNskFoOphJLa3JE0oJAUGGUOOAEgKcDTZILgqw6rezJayzl++XpbSFt4PETj0ZagQfm5bbEyX1rRT6Jt4QobIJfH3O7P0czhm2Iw/Ls545t6pps9ofgT7YmXKdkrQ3JjJDoDrilKUC+w8vskhTi9aBAFt1WHHIXK5a5VuIW2UR7ja7SfFIBStq2sSCCRonqYkgnfR1vrQs+mXewpSlQKUpQKUpQKUpQKUpQKUpQK8O6CCfx3Xutee4G4T7n+RtSv9BQV58NspVx4D4/u60tpVc8eg3FXp78SX2UukjZJ7K9+5qy6hPCVtes3DWCWiSkh6DjVsjOA+4WiK2lW/32DU2pbu2j4SpLMNhyTJcS200guLUfZKQNkn9gKgnB6XXuO4GRyFyC5lLsnJSJKVh5pE95clplYUpRCmmnW2dA6AaASAAEjZ5nkzWuOLvCtygiXeAzZI69E+Ds15uKhXQOtF8HZGhrZ6BI6OdZLbONuPr9mUiJ/gcZtEq5LjskI21HZU4UI2QE9I0PYDr2FWTc0ODwu6/crdk+USITDC75ld3dQpkkpfYjvmFHe8ipRV5sRGlAg60QEgJAAsXyG9feobxFiMrAOL8Sw24zkSpdmssODKkI8vB59DKUuODyJVorCiNknR7J964mOZTc7/APEBmWPN3N02jFrBZmvlAB4CfKcluurJB2SGURQNjoKOvck2zd/oWdSlKyFKUoFKUoFKUoFKUoFKUoFQbnG9Tcc4Xz2/21akTLdjVzlRlJ35B5EZxSNaBO/IDWh71OahHMXyK8Fet9zaQ7Du1ztNpfbWAQtEu4R45SQejsO60dg77BHVWdiWwWGYkNmLHQhtplCW20oACUpAAAAHWtDr9q2q8IBKe/zXo+3+9QQLOFNXXP8ABsYLalem/NyF06SUFqIylkJO+9h+bGcBH3b76rV5wQbvi9twxtr5hGV3y32iUwUBaH4BeD09pYIP0LhsSmz7dK6IOjWzjSV3jlLLry1fXX4doi27Hxb9q9OLMQlyW+4ATolxqbCBIAP8gAk60Na5Nx8k5rtcBTJW3h9ndurhKB4plTVGPHKVhQPkGY84FJGtOoO961rHvQsJZ/lD7ew99aqoPhkjOXXEr3yXIfTIXn2S3HII73pJQpUIrEeFsjfkDFjsKB2elAVIud71LsnEmQqtdwfg3a6R0WSzyGQorauc5xMSGseIJAEh9okgHxAJPQNSzFMetmI4xacUsjAYgWaCxb4jQGg2y02G0JH4ASkDrrqkusbP0db3rNKVkKUpQKUpQKUpQKUpQKUpQKgPK0Jy8IxawtvpbM3Kbc/9W9EQ1qnEfudRCRvrYH4qfVXuaOuSuUOO7OhA8WJNzvKiPcBqEuN337bnd9Eb8ewT3YJ+ntI/asqOkk/tWdaqG8vX6XjvHOQTrZIbYujsJcK1Kc3pVxkEMREaHZ8n3Wk9fkVJ3oavDnryMMOSSg0pzJbjNvaHkAAuxZEhxUMr0TtSYnyzZ/HpgdAdaXDEVm4DKuRUM+H8aXx2dHV5BSXITDTcOI6ggkFt5mMiQgg6IkV8OUXXsK4qYw/Dnm4tzuiI2K2EOk6Q+8kMoX0dn0mwt4/kNH3PRntistrxiyW/HrLEbi261xGoURhoBKGWGkBCEAfYBIAGvsK1bxf5Fd8keGU8q8d4CfRdYhy5eXXKO42FhbEJAbje4+lQmSorySNHcckbANWsj+kbqpuMFpyvlPkLkDwcLUWSxiVvU6noNQvNUhTZ9vFUh9xJIOyWADrxAq2vfur8k8bMRmlKVgKUpQKUpQKUpQKUpQKUpQYqDSZkaZzZbrey6v5i0YxMekN+J8fCXKjho79t7hPde9Tk9ioXarYgcv5JfA8HPUx+zwgkEn01IfuDih+ASHmyR76CSetVcfYmtV9yVFeveR4RjLXyzkd69C63Jh1SfNUSE0t5DiB5Akom/p+yAQArR15A1YCt6Oqqqfd7ZG5QyrOsjjBi14Fi7cdq57J9P5hS5dxb0N7AbiW1ftvvWtEEsex6d9fMudGmi2FWrju2h8LAK0KvM9KkhPvpDrENJJGioouadFCSfUk/KGbweOsFvGY3BSSi3RlLabKwgvPnSWWQSCApxxSEDo7KgACSAefwzj9zs+FIuGSRUsZBkUp++3hAQQpuRJX5pYUT2r0GfRjAn/ojoAAAAFc/EZIbzfKMQ4bNvNzgXGW1cL5GSso9SItwR0skgj3bXNkgg9C3r9zoHphJfkkvUXHtPeAMNdwXiHGLFJhPxJxgpm3FuQ8Xn/nZBL8guOEqK1l1xfkokknvZ9zYo9q8taDaQBroV639q555eWVySs0pSoFKUoFKUoFKUoFKUoFKUoMEge9V5x0qW/nvKL8tDwDOSRIsYuLJBYFltzm0A+yfUddGhseQUd7JFWC4fp31/wC6hXFl6cyO1Xi+PsMNOu5LeYaiypKg4mHNdhIUSnry9OMgEHsEEHsEUnuibLOkn+xr86Soqc0u0fBEOIkjPctuOSZARsoNktTzcVpCSnQIeVGtrZSdhbbknWwCavLM8jjYfiV7yyYy49Hsluk3F1tsErUhltTigANkkhJA6PdVV8L2KfKYy9m8hHd0iQrPbVeIHlaoCFIYcIAABefcmSQQACmSgEApreM1jcl1xtdS1sxm1OuuJbQgFSlKVoADskk/YV+duOmZObci2PkO4OrbOUyLrlluAVvxtURlu3Wxv6lEhDsee9M8daS5KdBAJqxeap71xt9p4vtr7jc3O5Ztry2VacYtiE+pPeBHaT6ILKVjpLshkn3AOxZ2Uq5nvMUeCWLRilpTDZRoJZEiXODukj28hEYHsB/LGidECTiI2+Q+Rm8HjQIUK1O3rIr2+YlmtDK/Bct4DaipZBDTKEgrccIISkEgKUQg8Xi3IeSDmWSYJyPdrHeJdpt9quzc+1W9cJtBlmUhcQtKedJLZiBYcKgVJfAI2kqVBIfJPHeP825xmHLGUQsWvFtaVYMat17ktRnXrQyy3JkzIjRPlIQ8+V7KCs+MRsEIUFoHZ4z5CwKyWK48q57nGMWKfmz/AOtSBOvMZAhwkhuPEjlalAANo9ILAJSH33dElWzu4SY8i86VArVznw/kMd6VjHJWOX5qOguvLtFxbnpaQNkrWWCrxSADsnQGjuoej4r8BvbqofG2L5xn8xvwC27Bjr4aaWr2Q5Jl+hGbVre0rdSRoggEEDE+LOzeuBdtK07bMfnwI8yTbn4DrzSHFxXygusKI2W1ltSkFST0Shak7B0SNE7lZClKUClKUClKUClKUHh06TUK4btVqtnHlukWSQXod7fmZE24WyjyNyluzlHRAI+qSfcAn3IBNSi9TUW60TLi6soRFZW+ogHpKUknodnofg/2PtUZ4dtN2xzhzCLDfY4audsxu2xJjX0gIfbitpWn6RrpQI6GvwNVfQivxJZM5CxCJx9bhKVc+Q7lExhtUVIU7HiSnm2psoAg6DUdxZCtKCVqbJBG9Wla7fDtFtj2q3R0RosNpLDDSBpLbaQAEgfgAACqDtH67yPzJj3KVvdbXZmL1Mttr+nfqWSNClNvyQVEgiRcHIykqQNqaixleRCiBY/L+V3C02uHh+KTAzlmXvi2WchHqKjA9yZykAjbcZnydPkUpUsNNeQU6gHplqSYRa5PHcdec8i5NyvIJVb4HqYnjYUNp9Bh4mfJRvRSXpaA0dABSbew4CpKkkbly5Iw3CeTrnaczya02L5+0W5+A7crg1GExfrSkutteooeamwGioDZAeb/AMwFa7OZYbxvb7fxXgNun5JdbLEjwWbPagHnIzQBQhct9RDccHwUSp1YWohRSlxR0fjG45zrkB5q6cuXyPAjMqUuLjuNyXm22dlQHr3DSH3yU+G0tJYbH1pWl4EKOeLbvpEIyTlnHc+y5q13CFe7jbLatqRExK1wVP3W6yVDyaeuLPQt0UEj00TVMhxaSpzwS0AqZRrZzLmSStMKycZQpARsBlq7XkhKjsEj/CMK1rRHzQ0o+xHVjYtiWNYXam7HieO22y25oqUiJb4yGGUlRJJCEADZJJJ1sk12dfily9ToVpB4CwNbqZmZKuecTEyDLQ9lMtVwQy6RryZjq1Gj+56ZaQPYewAFkNtpaSEJ1odDQ1qvpSs3K3ihSlKgUpSgUpSgUpSgUrBOqj9szzDLzf7li1qyi2y7xZiBcIDMhK345ISfrQDsDS07OtAkA6PVJLeoOLzjdXLLxDl01l/0pC7TIjRl+iHgJDqC0yCgghQLi0DRGvz1uuNzTfryuBauK8OubkHI84fXAamM787db20hU6cCAQlbbRCGyRr132AQQTrQ+JfOrPh+JWWLelOGPecltkVTTMdyQ++Gnvmiwyy2krcce+W9BAA/qeSdjRIiXG9j5vzq73nkq7Qf4HlZKhuM1InNpkT7famiSxGiRVEtMqPmt1x5/alOqKSx4Ib8e2OGUw87wa9pPneV2Th+7YRj2P47Jmedtm2a02W0NBbqUobaU0ooTtaI6Ex/BbiUqCS4gkEd1xcO4q5Szm8yc45au6LE7dWlxXrdZ3lIntQCCUQBNQsmM0FK83RFPquPNoV8z6aUsi18K43xLA0SJFmty1XKeEfqF1mPrlXCd4FRR8xJdUp10I8iEhSilAPigJSABJwppI0D0Dr7/j/apflkx8cZ/q7QybI4r4CwSVc5aLPiWMW4hx9xDIbb9VagkKISCpx1aikb0pa1Eb2T3Co3NnKWXN/McZ/DtkD0FYV6Vxyu5R7CxIA9ihrT8sA+4LkdAIIIBG6te93/AB2xNMO3+8woCJL6IscyH0t+s+o6S0jZBUskaCRsk9AE1G5OR5lf1NM4RYkwIrh+u636O40lKQoghuHtL7i+j04WU6IUFKHRxP2or+yZr8ROSZ2/iNwaxPBZCIAucaJMtLl9RMYStLbykS2Z8cgoWtA05GbJCgUlel+EtRYPiPH9fKvHJTrofwFO3vf3P6x+Ov7nf20cSbvxjwkyu+Z1msGHdr2ENv3K7yWhcLstCj6bLSEgF3xU6Q2wyjQLukJ8lneoOVORMvQUcX8SXQMLQsN3fLiuyRQvXR+WWhU1QBO9KYbCtaChvYtnleB2LZbudYTxVesywa7NdH04uNTLeod9/WqfI3se30jX71H+SuZLxxm7DRkAwSH+oKDcZiVlclqbJcJ14RordvdckL0SQlAJJAGgNmth7i3krLlpXyJzHdGoi0pLlqxFgWdnyA9jK8nJhAOtlDzfkRspAPgJRg/E/HfHhmSMRxCBb5lyINwneJdmz1D2XJkuFT0he+ypxalEkkndT6zsaXF2cZhnUOdcMj45uGLR2ltpgrmyNqnJKSVuJZUht5lIOkgPNtrJ2ShI1ufV5CUp3r7+9eqzbLdwKUpQKVgEfmtafcIdrhvXC4S2Y0WOkrdeecCENpHuVEkAAfck0G1XlSkg9qA+9Vev4gMSuskwuNrbduQXwstqXjUcPw2lA6IXPcU3DSR7lPreeiNJOxv4rsXOecLQvJsqtmA2vQKrbjQ/ULi4QdgquElpLbaSDpTaIhUCD4vHYIvj+iW5pyThmAojfxNkEaM/NUURYaErfmy1a/pjxmgp59X3KW0KIGzrQNUpkHGGXcrZKvkfC8Fb4ovjQW6zkk1/V5uD6QltCX4UVSoymFtobSHJa3nEoSU/LIISsXJh/E2B4HJk3DG7KW7jN2JVzlyHJs+SCd+Lsp9S3lpB0QkrIBHQGzuY/bqrjlcOYPzpj2N2M4TZ8xg3K53TM7vfY0GVd8lUmVcIEpE1AkwiGUsIS205GLam2Q02sNhR2CFGx27Nz3oH/iTgIGz/ANkTN/8A1ff/APd1C8nt8nF/iBxW3sLQm0ZxeHLyttLKh4XGFa5LT6lOElJ9VowSlAAIMR1WyVEi9QoJRvy3r+35p8ltk2IXBxzkl5p9GTcls7Ujxbcsdibhemd9q1JclbOt/gD8VtNYO274LveXZLc3G1AtqVcTCA0PbwhhlKgf/MK19tVx79zDAausrE8Fs0nM8kiKDUmFa3UBi3uEbHzspZDUXQ0S2Sp8pPkhlwA1pHjDI89Qt3mLIkyITilFGM2NbkW3JQShSEyXSQ/NWgpIKiWWVpV3HBANNe6NN3kHjXGr1csY42w93K8mhu/8xg45BbWtt8pSCJc1wojNu+JSSl54OFJGkqBr6/whzXm+3s6zmLiFvJUf0XDtuvqAJADt0kthakqGiQzHjqSToOKAJNk2ixWewW1iy2K1RLdAiJKI8WIwlllpJJOkISAEjZJ0AOya6FN/gh2G8Scd4E+9PxjGY7FylJCZV0kLXLuMrQ1t6Y+pb7p9+1rJJJJ2STUwCEp9hXqlZtt7ClKUClKUGN79qr7mG58tQrFFb4hx623O5yZYZfcmSkt/KslCv5yUqICiFeIPZIB2EOdpqwdU0KY3xuxUEHF/iEvUVi3ZFybZ8aYbShHr2W3InXKR4jRK5EhtEZKlAEnwhgbJKQgAA9OH8PfHC5qbplsKbmtwQ/8AMtv5VMcuqI72wfUYYeJjxVbHRYab0NgAAkVZlZrVyt6HyZZQw0hptKUpQAEhIAAAHQAHsK+tKVkK8k+3718ZctmGwuRJcS0y0hTjjiyAhCUjZJJ6AABOz1VXJ5Gybk7zh8LtR2bSl0tPZfdYy1wlhJUlYt7AUhcxW0gJe2mMQsLQ4+UqbNk2OH8TmYW7B1cf5TMuLcd2zZWi4el5JLrsNMKW3MKEH+oJjuuEkD6ejsEAjqnH+SOU2ZD+ez5+EYwslLdgtU4N3OQgHoy58dZ9IEgKDcRYOthTziVFscHPuHMeZj2uyS3Zd9uuZyJ1mu94ushTs2Yyuz3IhkLQkJZZStSlIZaDbSCpakJCyd2BxVdFcmcH4hfcpjRp5yvFoEu5MvsJW1IMqIhTqVt68SlXmoFOtaJGtV1yuMwmpzF9O9hVrxO147CjYNCtESx+KnYjdpbbREIWoqKmw0AjSlEqJG9kkkkkkyCubjuP2TE7FAxnGrVFtlqtcdEWHDitBtmOygAIQhI6AAAAArpVx77QpSlApSlApSlApSlApSlApSlB4U4EHRB/vVc5RzZj1svUnEMQgXDMcpi+Ads9kSlwxSrevm31FLEQEAkes4lSgCUJWQAZZmOLxczxyfjM+bcoke4Nek4/bZzkOS2N72h5pQWg9a2COiQdgkVCsX+HXjnElWlVsZvpRZmm22Iq77MRBWpG/wCa5BbdTEccJOytTRJIB9wK1PHW6NODxpknI0tVy5rvtvuUWM/tnFLO64LVHWFJUn5pZ8VznBpJ06lDIBP8knTlWy0y0y2hpptKEISEpSlOgB+APsK1LPYrLjsFFssFohW2Ggkpjw2EstJ2STpCAAOyfYe5roVLdiAchJknLOOlMNoKEZM+pwqe8CEmz3EdDR8zsjr7Dv7GtT4dnI6eGsZtEQEN46y9jg3vs259yET2lOwTGJBAAO9gAEVu8mqci3LB7mFhDcXKWg55HQ09ElRwPce630fnR70darjfDVEbicVgNSXJCJGS5NMS44jxJD98nOgAbO0gL0D90gHQ3oas+m19LWpSlYQpSlApSlApSlApSlApSlApSlApSlApSlBXnOc42jARey2lSLZe7JNdKjoIZbucZTqySDoBsLJOvYH296x8PbMqNwNxvGnocRKbxK0IfS4FBYcENoLBCgCDvfRAI+4HtUiz/CrXyNh11wi9yJbEG8RzGediLSh5sHR8kFSVAKBAIJBHVde3QYtrgx7bDQpDEVpLLSVLKyEJACQSokk6A7JJP3rVuPhr2u+NNulKVlClKUClKUClKUClKUH/2Q==" alt="firma-jorge" style="height: 80px;">
    </div>
    Jorge Largo Martinez <br/>
    <b>IN GEST SIERRA NEVADA SL</b><br/>
    <b>(B93714053)</b>
    </div>
  </div>
  </div>
  <div class="page-break"></div>
<?php endfor; ?>