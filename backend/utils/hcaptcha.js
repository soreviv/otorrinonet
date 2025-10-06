const https = require('https');
const querystring = require('querystring');

const HCAPTCHA_SECRET_KEY = process.env.HCAPTCHA_SECRET_KEY;

/**
 * Verifica un token de hCaptcha con la API oficial.
 * @param {string} token - El token 'h-captcha-response' enviado desde el cliente.
 * @returns {Promise<boolean>} - Resuelve a `true` si el token es válido, `false` en caso contrario.
 */
async function verifyHCaptcha(token) {
  if (!token) {
    return false;
  }

  const postData = querystring.stringify({
    secret: HCAPTCHA_SECRET_KEY,
    response: token,
  });

  const options = {
    hostname: 'hcaptcha.com',
    port: 443,
    path: '/siteverify',
    method: 'POST',
    headers: {
      'Content-Type': 'application/x-www-form-urlencoded',
      'Content-Length': Buffer.byteLength(postData),
    },
  };

  return new Promise((resolve, reject) => {
    const req = https.request(options, (res) => {
      let responseBody = '';
      res.on('data', (chunk) => {
        responseBody += chunk;
      });

      res.on('end', () => {
        try {
          const result = JSON.parse(responseBody);
          if (result.success) {
            resolve(true);
          } else {
            // Opcional: registrar los códigos de error para depuración
            // console.log('hCaptcha verification failed with error codes:', result['error-codes']);
            resolve(false);
          }
        } catch (error) {
          console.error('Error parsing hCaptcha response:', error);
          resolve(false); // Falla de forma segura
        }
      });
    });

    req.on('error', (error) => {
      console.error('Error verifying hCaptcha token:', error);
      resolve(false); // Falla de forma segura
    });

    req.write(postData);
    req.end();
  });
}

module.exports = { verifyHCaptcha };