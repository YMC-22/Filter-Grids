import { YMC_PATH } from "../core/constants.js";
export function YMCLoadTemplate(element, template, data) {
    if(typeof element === 'string') {
        this.el = document.querySelector(element);
    } else {
        this.el = element;
    }
    this.tempName = template;
    this.data = data || null;
    this.folderPath = YMC_PATH + 'src/admin/js-templates/';
};
YMCLoadTemplate.prototype.create = function(callback) {
    let req = new XMLHttpRequest();
    let that = this;
    req.open('get', this.folderPath + this.tempName + '.hbs', true);
    req.onreadystatechange = function() {
        if (req.readyState === 4 && req.status === 200) {
            let compiled = Handlebars.compile(req.response);
            that.el.innerHTML = compiled(that.data);
            callback();
        }
    };
    req.send();
};
YMCLoadTemplate.prototype.createAndWait = function(callback){
    let req = new XMLHttpRequest();
    let that = this;
    req.open('get', this.folderPath + this.tempName + '.hbs', true);
    req.onreadystatechange = function(){
        if (req.readyState === 4 && req.status === 200){
            let compiled = Handlebars.compile(req.response);
            callback(compiled, that.el);
        }
    };
    req.send();
};
