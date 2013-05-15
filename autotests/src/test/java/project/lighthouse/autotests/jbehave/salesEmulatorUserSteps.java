package project.lighthouse.autotests.jbehave;

import net.thucydides.core.annotations.Steps;
import org.jbehave.core.annotations.Given;
import org.jbehave.core.annotations.When;
import project.lighthouse.autotests.steps.SalesSteps;

public class SalesEmulatorUserSteps {

    @Steps
    SalesSteps salesSteps;

    @Given("the user opens sales emulator page")
    public void givenTheUserOpensSalesEmulatorPage() {
        salesSteps.openPage();
    }

    @When("the user adds the product with '$s' name, '$s' quantity and '$s' price to bill")
    public void whenTheUserMakesThePurchaseOfProductWithName(String productName, String quantity, String purchasePrice) {
        salesSteps.input("sales autocomplete field", productName);
        salesSteps.input("sales quantity", quantity);
        salesSteps.input("sales purchasePrice", purchasePrice);
        salesSteps.addToSales();
    }

    @When("the user makes the purchase")
    public void whenTheUSerMakesThePurchase() {
        salesSteps.makePurchase();//
    }
}
