package project.lighthouse.autotests.jbehave.stockMovement;

import net.thucydides.core.annotations.Steps;
import org.jbehave.core.annotations.Alias;
import org.jbehave.core.annotations.Then;
import org.jbehave.core.annotations.When;
import org.jbehave.core.model.ExamplesTable;
import project.lighthouse.autotests.steps.general.StockMovementSteps;

public class StockMovementEndUserSteps {

    @Steps
    StockMovementSteps stockMovementSteps;

    @When("пользователь* нажимает на кнопку добавления нового товара")
    public void whenUserClicksOnAddNewStockMovementProductButton() {
        stockMovementSteps.clickAddProductButton();
    }

    @Then("пользователь* проверяет, что список товаров содержит товары с данными $examplesTable")
    public void thenUserAssertsTheStockMovementProductListContainProductWithValues(ExamplesTable examplesTable) {
        stockMovementSteps.productCollectionExactCompare(examplesTable);
    }

    @Then("пользователь* проверяет, что сумма итого равна '$totalSum'")
    public void thenUserAssertsStockMovementTotalSum(String totalSum) {
        stockMovementSteps.assertTotalSum(totalSum);
    }

    @When("пользователь* нажимает на кнопку создания $type")
    public void whenTheUserClicksOnTheStockInAcceptButton() {
        stockMovementSteps.clickCreateButton();
    }

    @Then("пользователь* проверяет, что поле дата товародвижения заполнено сегодняшней датой")
    public void thenTheUserAssertsTheStockInDateIsSetAutomaticallyToNowDate() {
        stockMovementSteps.assertStockInDateIsNowDate();
    }

    @When("пользователь* в модальном окне товародвижения удаляет товар с названием '$name'")
    public void whenTheUserDeletesTheProductWithNameInTheEditStockInModalWindow(String name) {
        stockMovementSteps.clickStockMovementProductDeleteIcon(name);
    }

    @Then("пользователь* в модальном окне товародвижения проверяет, что количество продуктов равно '$count'")
    public void thenUserCheckProductRowsCount(Integer count) {
        stockMovementSteps.assertProductRowsCount(count);
    }
}
