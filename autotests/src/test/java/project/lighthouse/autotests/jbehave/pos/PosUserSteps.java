package project.lighthouse.autotests.jbehave.pos;

import net.thucydides.core.annotations.Steps;
import org.jbehave.core.annotations.Given;
import org.jbehave.core.annotations.Then;
import org.jbehave.core.annotations.When;
import org.jbehave.core.model.ExamplesTable;
import project.lighthouse.autotests.steps.pos.PosSteps;

public class PosUserSteps {

    @Steps
    PosSteps posSteps;

    @Given("пользователь открывает страницу кассы магазина с названием '$storeName'")
    public void givenUserOpenStorePosPageWithName(String storeName) {
        posSteps.navigateToPosPage(storeName);
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

    @Then("пользователь проверяет, что коллекция результатов поиска автокомплита содержит следующие конкретные данные $examplesTable")
    public void thenExactCompareWithExamplesTable(ExamplesTable examplesTable) {
        posSteps.exactComparePosAutocompleteResultsCollectionWithExamplesTable(examplesTable);
    }

    @Then("пользователь проверяет, что коллекция добавленных продуктов в чек содержит следующие конкретные данные $examplesTable")
    public void thenExactCompareReceiptCollectionWithExamplesTable(ExamplesTable examplesTable) {
        posSteps.exactCompareReceiptCollectionWithExamplesTable(examplesTable);
    }

    @Then("пользователь проверяет, что коллекция результатов поиска автокомплита пуста")
    public void thenCheckNoResults() {
        posSteps.checkNoResults();
    }

    @Then("пользователь проверяет, что чек получился на сумму '$totalSum'")
    public void thenUserAssertsReceiptTotalSum(String totalSum) {
        posSteps.assertReceiptTotalSum(totalSum);
    }

    @Then("пользователь проверяет, что последний добавленный продукт в чек прикреплен к концу чека")
    public void thenTheUsercheckTheLastAddedProductIsPinned() {
        posSteps.checkTheLastAddedProductIsPinned();
    }
}
