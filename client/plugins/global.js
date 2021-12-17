import Vue from 'vue'


var mixin = {
  data: function () {
    return {
      moment_format: 'DD/MM/YYYY HH:mm'
    }
  },
  methods: {
    formatSize(size) {
      if (size > 1024 * 1024 * 1024 * 1024) {
        return (size / 1024 / 1024 / 1024 / 1024).toFixed(2) + ' TB'
      } else if (size > 1024 * 1024 * 1024) {
        return (size / 1024 / 1024 / 1024).toFixed(2) + ' GB'
      } else if (size > 1024 * 1024) {
        return (size / 1024 / 1024).toFixed(2) + ' MB'
      } else if (size > 1024) {
        return (size / 1024).toFixed(2) + ' KB'
      }
      return size.toString() + ' B'
    },
    checkEmail(value) {
      const email_reg = "^\w+([-+.']\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$";
      return !!email_reg.test(value);
    },
    resetItems(array) {
      let result = array;
      let item = {};
      for (item in array) {
        result[item] = null;
      }
      return result;
    },
    setItems(array, fill_able) {
      let item = {};
      let result = fill_able;
      for (item in fill_able) {
        if (array[item]) {
          result[item] = array[item];
        }
      }
      return result;
    }
  }
}

Vue.mixin(mixin)
