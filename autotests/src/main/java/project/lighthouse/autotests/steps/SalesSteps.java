package project.lighthouse.autotests.steps;

import net.thucydides.core.annotations.Step;
import net.thucydides.core.steps.ScenarioSteps;
import project.lighthouse.autotests.pages.sales.SalesEmulatorManagerPage;

public class SalesSteps extends ScenarioSteps {

    SalesEmulatorManagerPage salesEmulatorManagerPage;

    @Step
    public void openPage() {
        salesEmulatorManagerPage.open();
    }

    @Step
    public void input(String elementName, String inputValue) {
        salesEmulatorManagerPage.input(elementName, inputValue);
    }

    @Step
    public void addToSales() {
        salesEmulatorManagerPage.addToSales();
    }

    @Step
    public void makePurchase() {
        salesEmulatorManagerPage.makePurchase();
    }
}
