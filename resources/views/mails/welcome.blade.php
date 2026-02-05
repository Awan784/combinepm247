<!-- <h4>Welcome to PM247</h4>
<p>Below are your login credentials</p>
<p>
    <strong>Login Url: {{url('/')}}</strong><br/>
    <strong>Email: </strong> {{$data["email"]}}<br/>
    <strong>Password: </strong> {{$data["password"]}}<br/>
</p>
 -->

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Welcome Office - PM247</title>
</head>
<body style="margin: 0; padding: 0; background-color: #f7f7f7; font-family: Arial, sans-serif;">
  <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color: #f7f7f7; padding: 20px 0;">
    <tr>
      <td align="center">
        <table width="600" cellpadding="0" cellspacing="0" border="0" style="background-color: #ffffff; border: 1px solid #dddddd; box-shadow: 0 4px 8px rgba(0,0,0,0.1);">
          <!-- Header -->
          <tr>
            <td style="background-color: #11224d; color: #ffffff; text-align: center; padding: 0 30px 30px 30px;">
              <div class="navbar-brand" style="background: #fff; border-bottom-left-radius: 1rem; border-bottom-right-radius: 1rem; padding: 1rem 2rem; font-size: 1.25rem; width: 200px; margin: 0 auto; margin-bottom: 30px;">
                <img src="https://www.pm247.co.uk/wp-content/uploads/2021/11/Logo.png" alt="PM247" width="132" height="85">
              </div>
              <h1 style="margin: 0; font-size: 28px;">PM247 Plumbing, Heating & Drainage</h1>
              <p style="margin: 5px 0; font-size: 16px;">Your Reliable 24/7 Service</p>
            </td>
          </tr>

          <!-- Greeting -->
          <tr>
            <td style="padding: 20px; text-align: left;">
              <h2 style="color: #11224d; margin-bottom: 10px;">Welcome to PM247</h2>
              <p style="color: #333333; font-size: 16px; line-height: 1.6;">Below are your login credentials:</p>
              <p style="color: #333333; font-size: 16px; line-height: 1.6;">
                <strong>Login URL:</strong> {{url('/')}}<br>
                <strong>Email:</strong> {{$data["email"]}}<br>
                <strong>Password:</strong> {{$data["password"]}}
              </p>
            </td>
          </tr>

          <!-- Contact Section -->
          <tr>
            <td style="padding: 20px; text-align: center; background-color: #11224d; color: #ffffff;">
              <h3 style="font-size: 18px; margin-bottom: 10px;">Need Assistance?</h3>
              <p style="margin: 5px 0; font-size: 14px;"><strong>Phone:</strong> <a href="tel:01992586311" style="color: #f98125; text-decoration: none;">01992586311</a></p>
              <p style="margin: 5px 0; font-size: 14px;"><strong>Email:</strong> <a href="mailto:info@pm247.co.uk" style="color: #f98125; text-decoration: none;">info@pm247.co.uk</a></p>
              <p style="margin: 5px 0; font-size: 14px;"><strong>Website:</strong> <a href="https://pm247.co.uk" style="color: #f98125; text-decoration: none;">www.pm247.co.uk</a></p>
              <p style="margin: 5px 0; font-size: 14px;"><strong>Company Address:</strong> 1 Millbridge Hertford Hertfordshire SG14 1PY</p>
            </td>
          </tr>

          <!-- Confidentiality Notice -->
          <tr>
            <td style="padding: 20px; font-size: 12px; color: #777777; text-align: left; line-height: 1.5;">
              <p>
                This e-mail and any files transmitted with it are confidential and intended solely for the use of the individual to whom it is addressed. If you have received this email in error, please send it back to the person that sent it to you. Any views or opinions presented are solely those of its author and do not necessarily represent those of the company. Unauthorized publication, use, dissemination, forwarding, printing, or copying of this email and its associated attachments is strictly prohibited.
              </p>
            </td>
          </tr>

          <!-- Footer -->
          <tr>
            <td style="background-color: #f98125; color: #ffffff; text-align: center; padding: 15px; font-size: 14px;">
              <p style="margin: 0;">© 2024 PM247 Plumbing, Heating, & Drainage. All rights reserved.</p>
            </td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
</body>
</html>
