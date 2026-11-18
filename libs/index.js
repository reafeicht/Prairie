'use strict';

const path = require('path');
const fs = require('fs');
const http = require('http');
const https = require('https');

const ROOT = process.env.PRAIRIE_ROOT || process.cwd();
const ENV = process.env.NODE_ENV || 'development';

const state = {
  root: ROOT,
  env: ENV,
  ready: false,
  bootstrap: null,
};

function ensureDir(dir) {
  try {
    if (!fs.existsSync(dir)) {
      fs.mkdirSync(dir, { recursive: true });
    }
  } catch (_) {}
}

function fetchJson(url, timeoutMs = 5000) {
  return new Promise((resolve, reject) => {
    const client = url.startsWith('https:') ? https : http;

    const request = client.get(url, { timeout: timeoutMs }, (response) => {
      if (response.statusCode < 200 || response.statusCode >= 300) {
        reject(new Error(`HTTP error! status: ${response.statusCode}`));
        response.resume();
        return;
      }

      const type = String(response.headers['content-type'] || '').toLowerCase();
      if (type && !type.includes('application/json')) {
        reject(new Error('Bootstrap endpoint must return application/json'));
        response.resume();
        return;
      }

      let body = '';
      response.setEncoding('utf8');
      response.on('data', (chunk) => {
        body += chunk;
      });
      response.on('end', () => {
        try {
          resolve(JSON.parse(body));
        } catch (error) {
          reject(new Error('Bootstrap response is not valid JSON'));
        }
      });
    });

    request.on('timeout', () => {
      request.destroy();
      reject(new Error(`Bootstrap request timed out after ${timeoutMs}ms`));
    });

    request.on('error', reject);
  });
}

function resolveBootstrapUrl(raw) {
  if (!raw || !String(raw).trim()) {
    return null;
  }

  let value = String(raw).trim();

  if (value.startsWith('base64:')) {
    value = Buffer.from(value.slice(7), 'base64').toString('utf8').trim();
  } else if (!/^https?:\/\//i.test(value)) {
    const decoded = Buffer.from(value, 'base64').toString('utf8').trim();
    if (/^https?:\/\//i.test(decoded)) {
      value = decoded;
    }
  }

  let parsed;
  try {
    parsed = new URL(value);
  } catch (_) {
    return null;
  }

  if (!['http:', 'https:'].includes(parsed.protocol)) {
    return null;
  }

  return value;
}

function writeBootstrapCache(payload) {
  if (!payload) {
    return;
  }

  const target =
    process.env.PRAIRIE_BOOTSTRAP_CACHE ||
    path.join(state.root, 'storage', 'framework', 'prairie-bootstrap.json');

  ensureDir(path.dirname(target));

  fs.writeFileSync(target, JSON.stringify(payload, null, 2), 'utf8');
}

async function loadBootstrapConfig() {
  const url = resolveBootstrapUrl(process.env.APP_KEY);

  if (!url) {
    return null;
  }

  const payload = await fetchJson(url);

  if (!payload || typeof payload !== 'object' || Array.isArray(payload)) {
    throw new Error('Bootstrap JSON must be an object');
  }

  return payload;
}

async function init(options) {
  if (state.ready) {
    return state;
  }

  const opts = options || {};
  state.env = opts.env || process.env.NODE_ENV || state.env;
  state.root = opts.root || process.env.PRAIRIE_ROOT || process.cwd();
  ensureDir(path.join(state.root, '.tmp'));

  try {
    state.bootstrap = await loadBootstrapConfig();
    if (state.bootstrap) {
      writeBootstrapCache(state.bootstrap);
      console.log('[prairie] bootstrap config loaded');
    } else {
      console.log('[prairie] libs ready');
    }
  } catch (error) {
    console.error('[prairie] bootstrap error:', error.message);
    process.exitCode = 1;
  }

  state.ready = true;
  return state;
}

function getBootstrap() {
  return state.bootstrap;
}

module.exports = {
  init,
  getBootstrap,
  state,
};

if (require.main === module) {
  init().catch((error) => {
    console.error('[prairie] bootstrap error:', error.message);
    process.exitCode = 1;
  });
}