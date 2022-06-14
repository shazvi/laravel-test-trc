describe('Admin edit resource page', () => {
    beforeEach(() => {
        cy.intercept("GET", "**/resources/*", {fixture: "GET.resource.json"});

        cy.visit(Cypress.env("HOST") + '/admin#/edit/33');
    });

    it('receives resource object from homepage via router', () => {
        cy.intercept("GET", "**/resources", {fixture: "GET.resources.json"});
        cy.visit(Cypress.env("HOST")+'/admin');
        cy.get('.resource-list-item').eq(4).click();
        cy.get('.resource-edit-btn').click();
        cy.get('#title-field').invoke('val').then(val => {
            expect(val).to.eq("Border syntax");
        });
    });

    it('fetches resource by ID from server', () => {
        cy.get('#title-field').invoke('val').then(val => {
            expect(val).to.eq("test html");
        });
        cy.get('#description-field').should('exist').invoke('val').then(val => {
            expect(val).to.eq("Lorem ipsum");
        })
        cy.get('#html-field').should('exist').invoke('val').then(val => {
            expect(val).to.eq("<div>Hello</div>");
        })
    });

    it('validates html fields', () => {
        cy.get('#title-field').clear();
        cy.get('#description-field').clear();
        cy.get('#html-field').clear();

        cy.get('.edit-resource-form').submit();
        cy.get('.invalid-feedback').should('have.length', 3);

        cy.get('#title-field').type('new title');
        cy.get('#description-field').type('new description');
        cy.get('#html-field').type('<div>new html snippet</div>');
        cy.get('.invalid-feedback').should('not.exist');
    });

    it('submits successfully', () => {
        cy.intercept("POST", "**/resources/*", {
            body: {"success": "Resource saved"}
        });

        cy.get('#title-field').type('new title');
        cy.get('#description-field').type('new description');
        cy.get('#html-field').type('<div>new html snippet</div>');
        cy.get('.edit-resource-form').submit();

        cy.get('.alert-success').contains('Resource saved.').should('exist');
    });
});
