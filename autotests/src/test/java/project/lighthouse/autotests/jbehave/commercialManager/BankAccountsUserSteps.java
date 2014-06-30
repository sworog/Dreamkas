package project.lighthouse.autotests.jbehave.commercialManager;

import net.thucydides.core.annotations.Steps;
import org.jbehave.core.annotations.Then;
import org.jbehave.core.annotations.When;
import org.jbehave.core.model.ExamplesTable;
import project.lighthouse.autotests.steps.commercialManager.BankAccountsSteps;

public class BankAccountsUserSteps {
    @Steps
    BankAccountsSteps bankAccountsSteps;

    ExamplesTable bankAccountData;

    @When("user clicks create new bank account link")
    public void userClicksCreateNewBankAccountLink() {
        bankAccountsSteps.clickCreateNewBankAccountLink();
    }

    @When("user fill bank account inputs $fieldInputTable")
    public void userFillBankAccountInputs(ExamplesTable fieldInputTable) {
        bankAccountsSteps.fillInputs(fieldInputTable);
        bankAccountData = fieldInputTable;
    }

    @When("user clicks create new bank account form button")
    public void userClicksCreateNewBankAccountFormButton() {
        bankAccountsSteps.clickCreateBankAccountFormButton();
    }

    @When("user clicks save bank account form button")
    public void userClicksSaveBankAccountFormButton() {
        bankAccountsSteps.clickSaveBankAccountFormButton();
    }

    @When("user clicks to bank accounts in list with bank '$bankName' and account '$account'")
    public void userClicksToBankAccountsInListWithBankAndAccount(String bankName, String account) {
        bankAccountsSteps.clickBankAccountListItemByBankAndAccount(bankName, account);
    }

    @When("user clicks to bank accounts in list with bank '$bankName'")
    public void userClicksToBankAccountsInListWithBank(String bankName) {
        bankAccountsSteps.clickBankAccountListItemByBank(bankName);
    }

    @Then("user checks bank account fields data")
    public void userChecksBankAccountsFieldsData() {
        bankAccountsSteps.checkBankAccountData(bankAccountData);
    }

    @Then("user checks bank account form the element field '$name' has error message '$errorMessage'")
    public void userChecksBankAccountFormTheElementFieldHasErrorMessage(String name, String errorMessage) {
        bankAccountsSteps.assertFieldErrorMessage(name, errorMessage);
    }

    @Then("user checks bank account list exists account '$account' and bank '$bankName'")
    public void userChecksBankAccountListExistsAccountAndBank(String account, String bankName) {
        bankAccountsSteps.assertExistsListItemWithAccountAndBank(account, bankName);
    }
}
