const scriptSrcValues = ["'self'", "'unsafe-inline'"];
const connectSrcValues = ["'self'"];
const fontSrcValues = ["'self'", 'https:', 'data:'];
const imgSrcValues = ["'self'", 'data:'];
const styleSrcValues = ["'self'", 'https:', "'unsafe-inline'"];

// Crisp settings
{
  scriptSrcValues.push('https://client.crisp.chat/');
  connectSrcValues.push('wss://client.relay.crisp.chat/', 'https://client.crisp.chat/static/', 'https://storage.crisp.chat/users/upload/');
  styleSrcValues.push('https://client.crisp.chat/');
  imgSrcValues.push('https://*.crisp.chat/');
  fontSrcValues.push('https://client.crisp.chat/static/');
}

// Sentry settings
if (process.env.SENTRY_URL) {
  const sentryUrl = new URL(process.env.SENTRY_URL);

  connectSrcValues.push(sentryUrl.toString());

  // Also allow subdomains for this URL
  sentryUrl.host = `*.${sentryUrl.host}`;
  connectSrcValues.push(decodeURIComponent(sentryUrl.toString()));
}

// Due to Next.js hot reload in development we need to allow `eval()`
// Ref: https://github.com/vercel/next.js/issues/14221
if (process.env.NODE_ENV !== 'production') {
  scriptSrcValues.push("'unsafe-eval'");
}

// Those headers are directly inspired from the default of https://github.com/helmetjs/helmet
// (they don't have a Next.js integration so dealing with it manually)
const securityHeaders = {
  'Content-Security-Policy': `default-src 'self';base-uri 'self';font-src ${fontSrcValues.join(
    ' '
  )};form-action 'self';frame-ancestors 'self';img-src ${imgSrcValues.join(' ')};object-src 'none';script-src ${scriptSrcValues.join(
    ' '
  )};script-src-attr 'none';connect-src ${connectSrcValues.join(' ')};style-src ${styleSrcValues.join(' ')};upgrade-insecure-requests`,
  'Cross-Origin-Embedder-Policy': 'require-corp',
  'Cross-Origin-Opener-Policy': 'same-origin',
  'Cross-Origin-Resource-Policy': 'same-origin',
  'Origin-Agent-Cluster': '?1',
  'Referrer-Policy': 'no-referrer',
  'Strict-Transport-Security': 'max-age=15552000; includeSubDomains',
  'X-Content-Type-Options': 'nosniff',
  'X-DNS-Prefetch-Control': 'off',
  'X-Download-Options': 'noopen',
  'X-Frame-Options': 'SAMEORIGIN',
  'X-Permitted-Cross-Domain-Policies': 'none',
  'X-XSS-Protection': '0',
};

module.exports = {
  securityHeaders: securityHeaders,
  assetsSecurityHeaders: {
    ...securityHeaders,
    'Cross-Origin-Resource-Policy': 'cross-origin', // Wanted to use `same-origin` but it blocks downloading `/assets/*` like styles and images from an email viewer
    'Access-Control-Allow-Origin': '*', // Needed to load font files from an email viewer
  },
  convertHeadersForNextjs: function (headers) {
    return Object.keys(headers).map((headerName) => {
      return {
        key: headerName,
        value: headers[headerName],
      };
    });
  },
};
