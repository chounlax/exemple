import { Controller } from '@hotwired/stimulus';

/*
 * Visionneuse plein écran d'une galerie, dans un <dialog> natif :
 * flèches gauche/droite pour naviguer, Échap ou clic à côté pour fermer.
 */
export default class extends Controller {
    static targets = ['item', 'dialog', 'stage', 'counter'];

    index = 0;

    open({ params }) {
        this.show(params.index);
        this.dialogTarget.showModal();
    }

    close() {
        this.dialogTarget.close();
    }

    next() {
        this.show((this.index + 1) % this.itemTargets.length);
    }

    previous() {
        this.show((this.index - 1 + this.itemTargets.length) % this.itemTargets.length);
    }

    keydown(event) {
        if (event.key === 'ArrowRight') {
            event.preventDefault();
            this.next();
        } else if (event.key === 'ArrowLeft') {
            event.preventDefault();
            this.previous();
        }
    }

    backdrop(event) {
        if (event.target === this.dialogTarget || event.target === this.stageTarget) {
            this.close();
        }
    }

    restoreFocus() {
        this.itemTargets[this.index]?.focus();
    }

    show(index) {
        this.index = index;
        const photo = this.itemTargets[index].querySelector('.photo').cloneNode(true);
        photo.classList.add('photo--large');
        this.stageTarget.replaceChildren(photo);
        this.counterTarget.textContent = `${index + 1} / ${this.itemTargets.length}`;
    }
}
