package project.lighthouse.autotests.steps.commercialManager;

import net.thucydides.core.annotations.Step;
import net.thucydides.core.steps.ScenarioSteps;
import org.jbehave.core.model.ExamplesTable;
import project.lighthouse.autotests.pages.commercialManager.bankAccounts.BankAccountsFormPage;
import project.lighthouse.autotests.pages.commercialManager.bankAccounts.BankAccountsListPage;

public class BankAccountsSteps extends ScenarioSteps {
    BankAccountsListPage bankAccountsListPage;
    BankAccountsFormPage bankAccountsFormPage;

    @Step
    public void clickCreateNewBankAccountLink() {
        bankAccountsListPage.createNewBankAccountLinkClick();
    }

    @Step
    public void fillInputs(ExamplesTable data) {
        bankAccountsFormPage.fieldInput(data);
    }

    @Step
    public void clickCreateBankAccountFormButton() {
        bankAccountsFormPage.createFormButtonClick();
    }

    @Step
    public void clickSaveBankAccountFormButton() {
        bankAccountsFormPage.saveFormButtonClick();
    }

    @Step
    public void clickBankAccountListItemByBankAndAccount(String bankName, String account) {
        bankAccountsListPage.bankAccountListItemByBankAndAccountClick(bankName, account);
    }

    @Step
    public void clickBankAccountListItemByBank(String bankName) {
        bankAccountsListPage.bankAccountListItemByBankClick(bankName);
    }

    @Step
    public void checkBankAccountData(ExamplesTable data) {
        bankAccountsFormPage.checkValues(data);
    }

    @Step
    public void assertFieldErrorMessage(String elementName, String expectedErrorMessage) {
        bankAccountsFormPage.getItems().get(elementName).getFieldErrorMessageChecker().assertFieldErrorMessage(expectedErrorMessage);
    }
}
