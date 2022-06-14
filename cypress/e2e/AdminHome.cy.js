describe('Admin home page', () => {
    beforeEach(() => {
        cy.intercept("GET", "**/resources", {fixture: "GET.resources.json"});
        cy.visit(Cypress.env("HOST")+'/admin');
    });

    it('loads the resources list', () => {
        cy.get(".list-group").should('have.length.greaterThan', 0);
    });

    it('has "Add Resource" nav item', () => {
        cy.get('.add-resource-link').should('exist');
    });

    it("has Edit and Delete buttons", () => {
        cy.get('.resource-list-item').first().click();
        cy.get('.resource-action-btns').should('exist');
    });

    it('opens/closes details section correctly', () => {
        cy.get('.select-resource-text').should('exist');
        cy.get('.resource-list-item').first().click();
        cy.get('h3').contains('Resource details').should('exist');
        cy.get('.details-close-btn').should('exist').click();
        cy.get('.select-resource-text').should('exist');
    });

    it('copies html snippet to clipboard', () => {
        cy.get('.resource-list-item').first().click();
        cy.get('.copy-clipboard-btn').click().then(() => {
            cy.window().then(win => {
                win.navigator.clipboard.readText().then(text => {
                    expect(text).to.eq("<div>Hello</div>")
                });
            });
        });
    });

    it('contains target="_blank" for new tab link', () => {
        cy.get('.resource-list-item').eq(2).click();
        cy.get('.resource-link').should('have.attr', 'target', '_blank')
    });

    it('removes item when deleted', () => {
        cy.intercept("DELETE", "**/resources/*", {
            body: {"success": "Resource deleted"}
        });

        cy.get('.resource-list-item').first().click();
        cy.get('.resource-delete-btn').click();
        cy.get('.resource-list-title').contains('test html').should('not.exist');
    });
})
