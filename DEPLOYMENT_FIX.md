# Deployment Fix Instructions

This document outlines the steps required to fix the "white screen" issue and missing styles/scripts on the VPS.

## 1. Configure Nginx CSP (Crucial Step)

Your current Nginx configuration is blocking:
1.  **Google Fonts** (`fonts.googleapis.com`)
2.  **Inline Styles** (Required by some Tailwind/Vite components)

You **must** update your Nginx configuration to allow these.

1.  Open your Nginx configuration file:

    ```bash
    sudo nano /etc/nginx/sites-available/otorrinonet
    ```

    *(Or whichever file you are using in `/etc/nginx/sites-enabled/`)*

2.  **Replace** the existing `add_header Content-Security-Policy` line with this **exact** line:

    ```nginx
    # TODO: Migrate to strict CSP using nonces within 2 weeks.
    # Current config allows unsafe-inline/unsafe-eval for immediate deployment stability.
    add_header Content-Security-Policy "default-src 'self'; script-src 'self' 'unsafe-inline' 'unsafe-eval'; style-src 'self' 'unsafe-inline' https://fonts.googleapis.com; font-src 'self' https://fonts.gstatic.com; img-src 'self' data:;";
    ```

    **Why?**
    *   `style-src ... https://fonts.googleapis.com`: Allows loading fonts from Google.
    *   `font-src ... https://fonts.gstatic.com`: Allows loading the font files themselves.
    *   `'unsafe-inline'`: Required because your compiled JS injects some styles dynamically and Tailwind uses some inline properties.

3.  Save the file (Ctrl+O, Enter) and exit (Ctrl+X).

4.  Test and reload Nginx:

    ```bash
    sudo nginx -t
    sudo systemctl reload nginx
    ```

## 2. Fix File Permissions (If you haven't already)

The web server needs permission to read the built assets.

```bash
sudo chown -R www-data:www-data /var/www/otorrinonet/public/build
```

## 3. Verify

1.  Clear your browser cache.
2.  Reload the page.
3.  The site should now load with fonts and styles.
