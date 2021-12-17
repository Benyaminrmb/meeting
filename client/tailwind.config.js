module.exports = {
  future: {
    // removeDeprecatedGapUtilities: true,
    // purgeLayersByDefault: true,
  },
  purge: [],
  theme: {
    extend: {},
  },
  content: [
    'node_modules/tv-*/dist/tv-*.umd.min.js',
  ],
  plugins: [

    require('daisyui'),
  ],
  daisyui: {
    themes: [

      'light',
      'forest',
      'synthwave'
    ],
    rtl: true,
  },
}
