
        </div>
        <!-- /.container-fluid -->

      </div>
      <!-- End of Main Content -->

      <!-- Footer -->
      <footer class="sticky-footer bg-white">
        <div class="container my-auto">
          <div class="copyright text-center my-auto">
              <span>TENE TENE JUSTICE AUBIN ETUDIANT EN GLBD APPLICATION DE GESTION DES STOCKS CAS DE PARAMEDIC EN VUE DE L'OBTENTION DE MON DIPLOME DE LICENCE EN GLBD</span>
          </div>
        </div>
      </footer>
      <!-- End of Footer -->

    </div>
    <!-- End of Content Wrapper -->

  </div>
  <!-- End of Page Wrapper -->

  <!-- Scroll to Top Button-->
  <a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
  </a>

  <!-- Logout Modal-->
  <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Près à laisser ?</h5>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body"><?php echo  $_SESSION['FIRST_NAME']; ?> voulez vous, vous deconnecter ?</div>
        <div class="modal-footer">
          <button class="btn btn-secondary" type="button" data-dismiss="modal">sortir</button>
          <a class="btn btn-primary" href="logout.php">Deconnexion</a>
        </div>
      </div>
    </div>
  </div>

  <!-- Bootstrap core JavaScript-->
  <script src="../vendor/jquery/jquery.min.js"></script>
  <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- Core plugin JavaScript-->
  <script src="../vendor/jquery-easing/jquery.easing.min.js"></script>

  <!-- Custom scripts for all pages-->
  <script src="../js/sb-admin-2.min.js"></script>

  <!-- Page level plugins -->
  <script src="../vendor/datatables/jquery.dataTables.min.js"></script>
  <script src="../vendor/datatables/dataTables.bootstrap4.min.js"></script>

  <!-- Page level custom scripts -->
  <script src="../js/demo/datatables-demo.js"></script>
  <script src="../js/city.js"></script> 
  

<!-- PROFILE OVERLAY NA MODAL -->
<div id="overlay" onclick="off()">
  <div id="text">I'm <?php echo  $_SESSION['FIRST_NAME']. ' '.$_SESSION['LAST_NAME'] ;?><BR>
    From <?php echo  $_SESSION['PROVINCE']. ' '.$_SESSION['CITY'] ;?></div>
</div>
<script>
function on() {
  document.getElementById("overlay").style.display = "block";
}

function off() {
  document.getElementById("overlay").style.display = "none";
}

//used in pos sa number only na textfields
function isNumberKey(evt)
      {
        var charCode = (evt.which) ? evt.which : evt.keyCode;
        if (charCode != 46 && charCode > 31 
        && (charCode < 48 || charCode > 57))
        return false;
        return true;
      }  
//end of used in pos sa number only na textfields

// Fonction pour sélectionner/désélectionner toutes les checkboxes
$(document).ready(function() {
    $("#select_all").on("click", function() {
        var checked = $(this).is(':checked');
        $('input[name="selected_ids[]"]').prop('checked', checked);
    });
});

// Fonction pour vérifier le stock avant de soumettre la transaction
function checkStockBeforeSubmit() {
    var cartItems = <?php echo isset($_SESSION['pointofsale']) ? json_encode($_SESSION['pointofsale']) : '[]'; ?>;
    var insufficientStock = [];
    
    // Vérifier chaque produit dans le panier
    for (var i = 0; i < cartItems.length; i++) {
        var item = cartItems[i];
        // Ici on pourrait faire une requête AJAX pour vérifier le stock en temps réel
        // Pour l'instant, on suppose que la vérification a déjà été faite lors de l'ajout au panier
    }
    
    return true; // Permettre la soumission si tout est OK
}
</script>

<!-- Assistant IA Chatbot -->
<link rel="stylesheet" href="../css/assistant-ia.css">
<div id="assistant-ia-bubble" onclick="document.getElementById('assistant-ia-chat').style.display='flex';this.style.display='none';">
  <span>💬</span>
</div>
<div id="assistant-ia-chat" style="display:none;">
  <div id="assistant-ia-header">
    Assistant IA
    <button id="assistant-ia-close" onclick="document.getElementById('assistant-ia-chat').style.display='none';document.getElementById('assistant-ia-bubble').style.display='flex';">×</button>
  </div>
  <div id="assistant-ia-messages"></div>
  <form id="assistant-ia-input" onsubmit="return sendAssistantIA();">
    <input type="text" id="assistant-ia-question" placeholder="Posez votre question..." autocomplete="off" required />
    <button type="submit">➤</button>
    <button type="button" id="assistant-ia-voice" onclick="startVoiceRecognition()">🎤</button>
  </form>
</div>
<script>
function appendMsg(msg, who) {
  var div = document.createElement('div');
  div.className = 'assistant-ia-msg ' + who;
  var bubble = document.createElement('div');
  bubble.className = 'bubble';
  bubble.textContent = msg;
  div.appendChild(bubble);
  document.getElementById('assistant-ia-messages').appendChild(div);
  document.getElementById('assistant-ia-messages').scrollTop = 99999;
}
function sendAssistantIA() {
  var input = document.getElementById('assistant-ia-question');
  var msg = input.value.trim();
  if (!msg) return false;
  appendMsg(msg, 'user');
  input.value = '';
  fetch('../pages/AssistantIA.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
    body: 'question=' + encodeURIComponent(msg)
  })
  .then(r => r.json())
  .then(data => {
    appendMsg(data.response, 'bot');
  });
  return false;
}

// Fonction pour la reconnaissance vocale
function startVoiceRecognition() {
  var voiceBtn = document.getElementById('assistant-ia-voice');
  var input = document.getElementById('assistant-ia-question');
  
  // Vérifier si la reconnaissance vocale est supportée
  if (!('webkitSpeechRecognition' in window) && !('SpeechRecognition' in window)) {
    alert('La reconnaissance vocale n\'est pas supportée par votre navigateur.');
    return;
  }
  
  // Créer l'objet de reconnaissance vocale
  var SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
  var recognition = new SpeechRecognition();
  
  // Configuration
  recognition.lang = 'fr-FR';
  recognition.continuous = false;
  recognition.interimResults = false;
  
  // Changer l'apparence du bouton pendant l'écoute
  voiceBtn.textContent = '🔴';
  voiceBtn.style.backgroundColor = '#ff4444';
  
  // Démarrer la reconnaissance
  recognition.start();
  
  // Quand la reconnaissance commence
  recognition.onstart = function() {
    appendMsg('🎤 Écoute en cours...', 'bot');
  };
  
  // Quand la reconnaissance se termine
  recognition.onend = function() {
    voiceBtn.textContent = '🎤';
    voiceBtn.style.backgroundColor = '';
  };
  
  // Quand un résultat est obtenu
  recognition.onresult = function(event) {
    var transcript = event.results[0][0].transcript;
    input.value = transcript;
    
    // Envoyer automatiquement la question
    sendAssistantIA();
  };
  
  // En cas d'erreur
  recognition.onerror = function(event) {
    voiceBtn.textContent = '🎤';
    voiceBtn.style.backgroundColor = '';
    appendMsg('❌ Erreur de reconnaissance vocale : ' + event.error, 'bot');
  };
}
</script>
<!-- Fin Assistant IA Chatbot -->

</body>

</html>

<?php
  include 'modal.php';
// JOB SELECT OPTION TAB
$sql = "SELECT DISTINCT TYPE, TYPE_ID FROM type";
$result = mysqli_query($db, $sql) or die ("Bad SQL: $sql");

$opt = "<select class='form-control' name='type'>";
  while ($row = mysqli_fetch_assoc($result)) {
    $opt .= "<option value='".$row['TYPE_ID']."'>".$row['TYPE']."</option>";
  }

$opt .= "</select>";

        $query = "SELECT ID, e.FIRST_NAME, e.LAST_NAME, e.GENDER, USERNAME, PASSWORD, e.EMAIL, PHONE_NUMBER, j.JOB_TITLE, e.HIRED_DATE, t.TYPE, l.PROVINCE, l.CITY
                      FROM users u
                      join employee e on u.EMPLOYEE_ID = e.EMPLOYEE_ID
                      join job j on e.JOB_ID=j.JOB_ID
                      join location l on e.LOCATION_ID=l.LOCATION_ID
                      join type t on u.TYPE_ID=t.TYPE_ID
                      WHERE ID =".$_SESSION['MEMBER_ID'];
        $result = mysqli_query($db, $query) or die(mysqli_error($db));
          while($row = mysqli_fetch_array($result))
          {  
                $zz= $row['ID'];
                $a= $row['FIRST_NAME'];
                $b=$row['LAST_NAME'];
                $c=$row['GENDER'];
                $d=$row['USERNAME'];
                $e=$row['PASSWORD'];
                $f=$row['EMAIL'];
                $g=$row['PHONE_NUMBER'];
                $h=$row['JOB_TITLE'];
                $i=$row['HIRED_DATE'];
                $j=$row['PROVINCE'];
                $k=$row['CITY'];
                $l=$row['TYPE'];
          }
      ?>

  <!-- User Edit Info Modal-->
  <div class="modal fade" id="settingsModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Modifier les informations de l'utilisateur</h5>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">
          <form role="form" method="post" action="settings_edit.php">
              <input type="hidden" name="id" value="<?php echo $zz; ?>" />

              <div class="form-group row text-left text-primary">
                <div class="col-sm-3" style="padding-top: 5px;">
                 Prénom:
                </div>
                <div class="col-sm-9">
                  <input class="form-control" placeholder="First Name" name="firstname" value="<?php echo $a; ?>" required>
                </div>
              </div>
              <div class="form-group row text-left text-primary">
                <div class="col-sm-3" style="padding-top: 5px;">
                 Nom:
                </div>
                <div class="col-sm-9">
                  <input class="form-control" placeholder="Last Name" name="lastname" value="<?php echo $b; ?>" required>
                </div>
              </div>
              <div class="form-group row text-left text-primary">
                <div class="col-sm-3" style="padding-top: 5px;">
                 Sexe:
                </div>
                <div class="col-sm-9">
                  <select class='form-control' name='gender' required>
                    <option value="" disabled selected hidden>Sélectionner le sexe</option>
                    <option value="Male">Masculin</option>
                    <option value="Female">Féminin</option>
                  </select>
                </div>
              </div>
              <div class="form-group row text-left text-primary">
                <div class="col-sm-3" style="padding-top: 5px;">
                 Nom d'utilisateur:
                </div>
                <div class="col-sm-9">
                  <input class="form-control" placeholder="Username" name="username" value="<?php echo $d; ?>" required>
                </div>
              </div>
              <div class="form-group row text-left text-primary">
                <div class="col-sm-3" style="padding-top: 5px;">
                 Mot de passe:
                </div>
                <div class="col-sm-9">
                  <input type="password" class="form-control" placeholder="Password" name="password" value="" required>
                </div>
              </div>
              <div class="form-group row text-left text-primary">
                <div class="col-sm-3" style="padding-top: 5px;">
                 Adresse email:
                </div>
                <div class="col-sm-9">
                  <input class="form-control" placeholder="Email" name="email" value="<?php echo $f; ?>" required>
                </div>
              </div>
              <div class="form-group row text-left text-primary">
                <div class="col-sm-3" style="padding-top: 5px;">
                Numéro de téléphone:
                </div>
                <div class="col-sm-9">
                   <input class="form-control" placeholder="Contact #" name="phone" value="<?php echo $g; ?>" required>
                </div>
              </div>
              <div class="form-group row text-left text-primary">
                <div class="col-sm-3" style="padding-top: 5px;">
                 Rôle:
                </div>
                <div class="col-sm-9">
                  <input class="form-control" placeholder="Role" name="role" value="<?php echo $h; ?>" readonly>
                </div>
              </div>
              <div class="form-group row text-left text-primary">
                <div class="col-sm-3" style="padding-top: 5px;">
                 Date d'embauche:
                </div>
                <div class="col-sm-9">
                  <input class="form-control" placeholder="Hired Date" name="hireddate" value="<?php echo $i; ?>" required>
                </div>
              </div>
              <div class="form-group row text-left text-primary">
                <div class="col-sm-3" style="padding-top: 5px;">
                 Province:
                </div>
                <div class="col-sm-9">
                  <input class="form-control" placeholder="Province" name="province" value="<?php echo $j; ?>" required>
                </div>
              </div>
              <div class="form-group row text-left text-primary">
                <div class="col-sm-3" style="padding-top: 5px;">
                 Ville / Commune:
                </div>
                <div class="col-sm-9">
                  <input class="form-control" placeholder="City / Municipality" name="city" value="<?php echo $k; ?>" required>
                </div>
              </div>
              <div class="form-group row text-left text-primary">
                <div class="col-sm-3" style="padding-top: 5px;">
                  Type de compte:
                </div>
                <div class="col-sm-9">
                  <input class="form-control" placeholder="Account Type" name="type" value="<?php echo $l; ?>" readonly>
                </div>
              </div>
              <hr>
            <button type="submit" class="btn btn-success"><i class="fa fa-check fa-fw"></i>Enregistrer</button>
            <button class="btn btn-secondary" type="button" data-dismiss="modal">sortir</button>      
          </form>  
        </div>
      </div>
    </div>
  </div>