describe('Admin new resource page', () => {
    beforeEach(() => {
        cy.visit(Cypress.env("HOST")+'/admin#/add');
    });

    it('loads page with two fields', () => {
        cy.get('#title-field').should('exist').should('have.attr', 'type', 'text');
        cy.get('#type-field').should('exist');
    });

    it('shows html fields when html is selected in dropdown', () => {
        cy.get('#type-field').select('1');
        cy.get('#description-field').should('exist');
        cy.get('#html-field').should('exist');
    });

    it('shows link fields when link is selected in dropdown', () => {
        cy.get('#type-field').select('2');
        cy.get('#link-field').should('exist');
        cy.get('#type-chk-box').should('exist').should('have.attr', 'type', 'checkbox');
    });

    it('shows pdf fields when pdf is selected in dropdown', () => {
        cy.get('#type-field').select('3');
        cy.get('#pdf-file').should('exist').should('have.attr', 'type', 'file');
    });

    it('validates html fields', () => {
        cy.get('#type-field').select('1');
        cy.get('.add-resource-form').submit();
        cy.get('.invalid-feedback').should('have.length', 3);

        cy.get('#title-field').type('new title');
        cy.get('#description-field').type('new description');
        cy.get('#html-field').type('<div>new html snippet</div>');
        cy.get('.invalid-feedback').should('not.exist');
    });

    it('validates link fields', () => {
        cy.get('#type-field').select('2');
        cy.get('.add-resource-form').submit();
        cy.get('.invalid-feedback').should('have.length', 2);

        cy.get('#title-field').type('new title');
        cy.get('#link-field').type('https://new.link');
        cy.get('.invalid-feedback').should('not.exist');
    });

    it('validates pdf fields', () => {
        cy.get('#type-field').select('3');
        cy.get('.add-resource-form').submit();
        cy.get('.invalid-feedback').should('have.length', 2);
    });

    it('submits successfully', () => {
        cy.intercept("POST", "**/resources", {
            body: {"success": "Resource saved"}
        });

        cy.get('#type-field').select('1');
        cy.get('#title-field').type('new title');
        cy.get('#description-field').type('new description');
        cy.get('#html-field').type('<div>new html snippet</div>');
        cy.get('.add-resource-form').submit();

        cy.get('.alert-success').contains('Resource saved.').should('exist');
    });
})
