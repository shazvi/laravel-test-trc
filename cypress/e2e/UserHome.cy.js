describe('Regular user home page', () => {
    beforeEach(() => {
        cy.intercept("GET", "**/resources", {fixture: "GET.resources.json"});
        cy.visit(Cypress.env("HOST"));
    });

    it("shouldn't have 'Add Resource' nav item", () => {
        cy.get('.add-resource-link').should('not.exist');
    });

    it("shouldn't have Edit and Delete buttons", () => {
        cy.get('.resource-list-item').first().click();
        cy.get('.resource-action-btns').should('not.exist');
    });
})
