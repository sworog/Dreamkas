package project.lighthouse.autotests.steps.commercialManager;

import net.thucydides.core.annotations.Step;
import net.thucydides.core.steps.ScenarioSteps;
import org.jbehave.core.model.ExamplesTable;
import project.lighthouse.autotests.pages.commercialManager.legalDetails.LegalDetailsFormPage;
import project.lighthouse.autotests.pages.commercialManager.oraganization.OrganizationFormPage;
import project.lighthouse.autotests.pages.commercialManager.oraganization.OrganizationListPage;
import project.lighthouse.autotests.steps.CommonSteps;

public class OrganizationSteps extends ScenarioSteps {
    CommonSteps commonSteps;

    OrganizationFormPage organizationFormPage;
    OrganizationListPage organizationListPage;

    @Step
    public void navigateToCreateOrganizationPage() {
        organizationFormPage.open();
    }

    @Step
    public void navigateToCompanyPage() {
        organizationListPage.open();
    }

    @Step
    public void clickCreateButton() {
        organizationFormPage.createButtonClick();
    }

    @Step
    public void clickEditButton() {
        organizationFormPage.saveButtonClick();
    }

    @Step
    public void clickCreateNewOrganizationLink() {
        organizationListPage.clickCreateNewOrganizationLink();
    }

    @Step
    public void fillInputs(ExamplesTable data) {
        organizationFormPage.fieldInput(data);
    }

    @Step
    public void clickOrganizationListItemByName(String name) {
        organizationListPage.clickOrganizationListItemByName(name);
    }

    @Step
    public void checkOrganizationData(ExamplesTable data) {
        organizationFormPage.checkValues(data);
    }

    @Step
    public void assertFieldErrorMessage(String elementName, String expectedErrorMessage) {
        organizationFormPage.getItems().get(elementName).getFieldErrorMessageChecker().assertFieldErrorMessage(expectedErrorMessage);
    }

    @Step
    public void createOrganizationWithName(String name) {
        organizationFormPage.open();
        clickCreateNewOrganizationLink();
        organizationFormPage.input("name", name);
        clickCreateButton();
    }

    @Step
    public void navigateToOrganizationPageByName(String name) {
        organizationListPage.open();
        clickOrganizationListItemByName(name);
    }

    @Step
    public void clickLegalDetailsLink() {
        organizationFormPage.legalDetailsLinkClick();
    }

    @Step
    public void clickBankAccountsListLink() {
        organizationFormPage.bankAccountsListLink();
    }
}
