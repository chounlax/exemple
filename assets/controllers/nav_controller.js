import { Controller } from '@hotwired/stimulus';

/*
 * Menu principal sur mobile : ouvre et ferme la navigation.
 */
export default class extends Controller {
    static targets = ['toggle', 'menu'];

    toggle() {
        const open = this.toggleTarget.getAttribute('aria-expanded') !== 'true';
        this.toggleTarget.setAttribute('aria-expanded', String(open));
        this.element.classList.toggle('is-open', open);
    }
}
