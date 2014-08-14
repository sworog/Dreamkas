package project.lighthouse.autotests.jbehave.stockMovement;

import net.thucydides.core.annotations.Steps;
import org.jbehave.core.annotations.Alias;
import org.jbehave.core.annotations.Given;
import project.lighthouse.autotests.steps.stockMovement.StockMovementSteps;

public class GivenStockMovementUserSteps {

    @Steps
    StockMovementSteps stockMovementSteps;

    @Given("the user opens the stock movement page")
    @Alias("пользователь открывает страницу товародвижения")
    public void givenTheUserOpensTheStockMovementPage() {
        stockMovementSteps.stockMovementPageOpen();
    }
}
