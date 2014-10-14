package ru.dreamkas.jbehave.stockMovement;

import net.thucydides.core.annotations.Steps;
import org.jbehave.core.annotations.Alias;
import org.jbehave.core.annotations.Given;
import ru.dreamkas.steps.stockMovement.StockMovementSteps;

public class GivenStockMovementUserSteps {

    @Steps
    StockMovementSteps stockMovementSteps;

    @Given("the user opens the stock movement page")
    @Alias("пользователь открывает страницу товародвижения")
    public void givenTheUserOpensTheStockMovementPage() {
        stockMovementSteps.stockMovementPageOpen();
    }
}
