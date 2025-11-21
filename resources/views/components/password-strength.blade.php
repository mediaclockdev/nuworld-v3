<!-- resources/views/components/password-strength.blade.php -->
<div class="mb-4 required">
  <label for="newpassword" class="form-label">New Password</label>
  <div class="input-group input-group-merge">
    <input type="password" name="newpassword" id="newpassword" class="form-control " placeholder="Enter a new password">
    <div class="input-group-text" data-password="false">
      <span class="password-eye"></span>
    </div>
  </div>
  <div id="password-strength" style="display: none">
    <small id="strength-message"></small>
    <div id="strength-bar" style="height: 6px; background-color: #e0e0e0; border-radius: 4px;">
      <div id="strength-progress" style="width: 0; height: 100%; background-color: red; border-radius: 4px;"></div>
    </div>
  </div>
  <div class="newpassword-error-container"></div>
</div>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    const passwordInput = document.getElementById('newpassword');
    const strengthMessage = document.getElementById('strength-message');
    const strengthProgress = document.getElementById('strength-progress');

    function resetPasswordBar() {
      document.getElementById('password-strength').style.display = 'none';
      strengthMessage.textContent = '';
      strengthProgress.style.width = '0%';
    }

    passwordInput.addEventListener('input', function() {
      document.getElementById('password-strength').style.display = 'block';
      const password = passwordInput.value;
      const strength = getPasswordStrength(password);
      updateStrengthDisplay(strength);
    });

    function getPasswordStrength(password) {
      let strength = 0;
      if (password.length >= 8) strength++;
      if (/[A-Z]/.test(password)) strength++;
      if (/[0-9]/.test(password)) strength++;
      if (/[@$!%*?&#-:]/.test(password)) strength++;
      return strength;
    }

    function updateStrengthDisplay(strength) {
      const strengthLevels = [{
          message: 'Too Weak',
          width: '10%',
          color: 'red'
        },
        {
          message: 'Very Weak',
          width: '25%',
          color: 'red'
        },
        {
          message: 'Weak',
          width: '50%',
          color: 'orange'
        },
        {
          message: 'Moderate',
          width: '75%',
          color: 'yellowgreen'
        },
        {
          message: 'Strong',
          width: '100%',
          color: 'green'
        }
      ];

      const {
        message,
        width,
        color
      } = strengthLevels[strength] || strengthLevels[0];
      strengthMessage.textContent = message;
      strengthMessage.style.color = color;
      strengthProgress.style.width = width;
      strengthProgress.style.backgroundColor = color;
    }
  });
</script>
