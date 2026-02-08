<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>Redefinição de Senha</title>
    <style>
        @media only screen and (max-width: 600px) {
            .inner-body {
                width: 100% !important;
            }
            
            .footer {
                width: 100% !important;
            }
        }
        
        @media only screen and (max-width: 500px) {
            .button {
                width: 100% !important;
            }
        }
        
        /* Base */
        body, body *:not(html):not(style):not(br):not(tr):not(code) {
            box-sizing: border-box;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif,
            'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol';
            position: relative;
        }
        
        body {
            -webkit-text-size-adjust: none;
            background-color: #ffffff;
            color: #333333;
            height: 100%;
            line-height: 1.4;
            margin: 0;
            padding: 0;
            width: 100% !important;
        }
        
        .body {
            background-color: #f2f2f2;
            border-bottom: 1px solid #f2f2f2;
            border-top: 1px solid #f2f2f2;
            margin: 0;
            padding: 0;
            width: 100%;
        }
        
        .inner-body {
            background-color: #ffffff;
            border-color: #e8e5ef;
            border-radius: 5px;
            border-width: 1px;
            box-shadow: 0 2px 0 rgba(0, 0, 150, 0.025), 2px 4px 0 rgba(0, 0, 150, 0.015);
            margin: 0 auto;
            padding: 0;
            width: 570px;
        }
        
        /* Header */
        .header {
            padding: 20px 35px;
            text-align: center;
            border-bottom: 1px solid #e8e5ef;
        }
        
        .header a {
            color: #3d4852;
            font-size: 19px;
            font-weight: bold;
            text-decoration: none;
        }
        
        /* Content */
        .content {
            margin: 0;
            padding: 25px;
        }
        
        .greeting {
            color: #3d4852;
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 15px;
        }
        
        p {
            color: #3d4852;
            font-size: 16px;
            line-height: 1.5em;
            margin-top: 0;
            text-align: left;
        }
        
        /* Button */
        .action {
            margin: 30px auto;
            padding: 0;
            text-align: center;
            width: 100%;
        }
        
        .button {
            border-radius: 4px;
            color: #fff;
            display: inline-block;
            overflow: hidden;
            text-decoration: none;
            background-color: #3490dc;
            border-bottom: 8px solid #3490dc;
            border-left: 18px solid #3490dc;
            border-right: 18px solid #3490dc;
            border-top: 8px solid #3490dc;
            box-shadow: 0 2px 3px rgba(0, 0, 0, 0.16);
            font-size: 16px;
            font-weight: bold;
        }
        
        /* Footer */
        .footer {
            margin: 0 auto;
            padding: 0;
            text-align: center;
            width: 570px;
        }
        
        .footer p {
            color: #b0adc5;
            font-size: 12px;
            text-align: center;
        }
    </style>
</head>
<body>
    <table class="body" width="100%" cellpadding="0" cellspacing="0" role="presentation">
        <tr>
            <td align="center">
                <table class="inner-body" width="570" cellpadding="0" cellspacing="0" role="presentation">
                    <!-- Header -->
                    <tr>
                        <td class="header">
                            <a href="https://seusiteaqui.com" style="display: inline-block;">
                                Clube Privê
                            </a>
                        </td>
                    </tr>
                    
                    <!-- Content -->
                    <tr>
                        <td class="content">
                            <div class="greeting">Olá, {{ $userName }}!</div>
                            
                            <p>Você está recebendo este email porque recebemos uma solicitação de redefinição de senha para sua conta.</p>
                            
                            <p>Ela ocorreu em {{ $dateTime }}.</p>
                            
                            <table class="action" align="center" width="100%" cellpadding="0" cellspacing="0" role="presentation">
                                <tr>
                                    <td align="center">
                                        <a href="{{ $resetUrl }}" class="button" target="_blank" style="color: #fff;">Redefinir Senha</a>
                                    </td>
                                </tr>
                            </table>
                            
                            <p>Este link de redefinição de senha expirará em {{ $expireMinutes }} minutos.</p>
                            
                            <p>Se você não solicitou uma redefinição de senha, nenhuma ação é necessária.</p>
                            
                            <p>Atenciosamente,<br>Equipe Clube Privê</p>
                        </td>
                    </tr>
                    
                    <!-- Footer -->
                    <tr>
                        <td>
                            <table class="footer" align="center" width="570" cellpadding="0" cellspacing="0" role="presentation">
                                <tr>
                                    <td align="center" style="padding: 20px;">
                                        <p>© 2025 Clube Privê. Todos os direitos reservados.</p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>