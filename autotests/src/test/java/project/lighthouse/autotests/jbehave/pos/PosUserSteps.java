package project.lighthouse.autotests.jbehave.pos;

import net.thucydides.core.annotations.Steps;
import org.jbehave.core.annotations.Alias;
import org.jbehave.core.annotations.Given;
import org.jbehave.core.annotations.Then;
import org.jbehave.core.annotations.When;
import org.jbehave.core.model.ExamplesTable;
import org.json.JSONException;
import project.lighthouse.autotests.steps.pos.PosSteps;

import java.io.IOException;

public class PosUserSteps {

    @Steps
    PosSteps posSteps;

    @Given("пользователь открывает страницу кассы магазина с названием '$storeName'")
    public void givenUserOpenStorePosPageWithName(String storeName) {
        posSteps.navigateToPosPage(storeName);
    }

    @Given("пользователь открывает страницу запуска кассы")
    public void givenUserOpenPosLaunchPage() {
        posSteps.openPosLaunchPage();
    }

    @When("пользователь нажимает на кнопку Далее на странице выбора кассы")
    public void whenUserClicksOnFurtherButton() {
        posSteps.choosePosConfirmation();
    }

    @When("пользователь нажимает на товарную позицию в чеке с названием '$name'")
    public void whenUserClicksOnReceiptObjectWithName(String name) {
        posSteps.receiptObjectClickByLocator(name);
    }

    @When("пользователь нажимает на кнопку плюсик чтобы увеличить количество товарной позиции на единицу")
    public void whenUserClicksOnPlusButton() {
        posSteps.clickOnPlusButton();
    }

    @When("пользователь нажимает на кнопку минус чтобы уменьшить количество товарной позиции на единицу")
    public void whenUserClicksOnMinusButton() {
        posSteps.clickOnMinusButton();
    }

    @When("пользователь нажимает на кнопку очистки чека")
    public void whenTheUserClicksOnReceiptClearButton() {
        posSteps.clearReceipt();
    }

    @When("пользователь подтверждает очищение чека")
    public void whrnTheUserConfirmsReceiptClear() {
        posSteps.confirmClearReceipt();
    }

    @When("пользователь совершает продажу")
    public void whenUserRegistersSale() {
        posSteps.clickOnRegisterSaleButton();
    }

    @When("пользователь нажимает на кнопку продолжить работу")
    public void whenUserClicksOnContinueWorkButton() {
        posSteps.clickOnContinueButton();
    }

    @When("пользователь нажимает на кнопку чтобы скрыть боковое меню навигации кассы")
    @Alias("пользователь нажимает на кнопку чтобы показать боковое меню навигации кассы")
    public void whenTheUserInteractsWithCashRegistrySideMenuButton() {
        posSteps.clickOnSideMenuInteractionButton();
    }

    @When("пользователь нажимает на ссылку с названием История продаж в боковом меню кассы")
    public void whenUserClickOnSaleHistorySideMenuLink() {
        posSteps.clickOnSaleHistorySideMenuLink();
    }

    @When("пользователь нажимает на ссылку с названием 'Сменить магазин' в боковом меню кассы")
    public void whenUserСlickOnChangeStoreSideMenuLink() {
        posSteps.clickOnChangeStoreSideMenuLink();
    }

    @Then("пользователь проверяет, что ссылка с названием 'Касса' активна (выбрана) в боковом меню навигации кассы")
    public void thenUserAssertCashRegistrySideMenuLinkIsActive() {
        posSteps.assertCashRegistrySideMenuLinkIsActive();
    }

    @When("пользователь вводит количество '$quantity' продукту с именем '$productName' для совершения возврата")
    @Alias("пользователь вводит количество c значением quantity продукту с именем '$productName' для совершения возврата")
    public void whenUserSetRefundProductQuantity(String quantity, String productName) {
        posSteps.setRefundProductQuantityByName(productName, quantity);
    }

    @When("пользователь увеличивает количество на единицу продукту с именем '$productName' путем нажатия на кнопку с плюсом для совершения возврата")
    public void clickOnRefundProductPlusButtonByName(String productName) {
        posSteps.clickOnRefundProductPlusButtonByName(productName);
    }

    @When("пользователь уменьшивает количество на единицу продукту с именем '$productName' путем нажатия на кнопку с минусом для совершения возврата")
    public void clickOnRefundProductMinusButtonByName(String productName) {
        posSteps.clickOnRefundProductMinusButtonByName(productName);
    }

    @When("пользователь нажимает на кнопку возврата")
    public void whenTheUserClicksOnRefundButton() {
        posSteps.clickOnRefundButton();
    }

    @Then("пользователь проверяет, что коллекция результатов поиска автокомплита содержит следующие конкретные данные $examplesTable")
    public void thenExactCompareWithExamplesTable(ExamplesTable examplesTable) {
        posSteps.exactComparePosAutocompleteResultsCollectionWithExamplesTable(examplesTable);
    }

    @Then("пользователь проверяет, что коллекция добавленных продуктов в чек содержит следующие конкретные данные $examplesTable")
    public void thenExactCompareReceiptCollectionWithExamplesTable(ExamplesTable examplesTable) {
        posSteps.exactCompareReceiptCollectionWithExamplesTable(examplesTable);
    }

    @Then("пользователь проверяет, что коллекция результатов поиска автокомплита пуста")
    public void thenUserChecksPostAutoCompleteCollectionContainsNoResults() {
        posSteps.checkPostAutoCompleteCollectionContainsNoResults();
    }

    @Then("пользователь проверяет, что коллекция товарных позиций в чеке пуста")
    public void thenUserCheckReceiptCollectionContainsNoResults() {
        posSteps.checkReceiptCollectionContainsNoResults();
    }

    @Then("пользователь проверяет, что чек получился на сумму '$totalSum'")
    public void thenUserAssertsReceiptTotalSum(String totalSum) {
        posSteps.assertReceiptTotalSum(totalSum);
    }

    @Then("пользователь проверяет, что последний добавленный продукт в чек прикреплен к концу чека")
    public void thenTheUsercheckTheLastAddedProductIsPinned() {
        posSteps.checkTheLastAddedProductIsPinned();
    }

    @Then("пользователь c адресом электронной почты '$email' проверяет, что у товара с именем '$productName' в магазине с именем '$storeName' остатки равны '$inventory'")
    public void thenUserChecksStoreProductHasInventory(String email, String productName, String storeName, String inventory) throws IOException, JSONException {
        posSteps.assertStoreProductInventory(email, storeName, productName, inventory);
    }

    @Then("пользователь проверяет, что заголовок успешной продажи гласит Выдайте сдачу '$value'")
    public void thenTheUserChecksReceiptSuccessTitle(String value) {
        posSteps.assertSuccessTitle(value);
    }

    @Then("пользователь проверяет, что количество равно '$quantity' продукта с именем '$name'")
    public void thenUserAssertsRefundProductQuantity(String quantity, String name) {
        posSteps.assertRefundPorductQuantity(name, quantity);
    }
}
