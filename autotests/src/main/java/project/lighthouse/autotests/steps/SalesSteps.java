package project.lighthouse.autotests.steps;

import net.thucydides.core.annotations.Step;
import net.thucydides.core.pages.Pages;
import net.thucydides.core.steps.ScenarioSteps;
import project.lighthouse.autotests.pages.sales.SalesEmulatorManagerPage;

public class SalesSteps extends ScenarioSteps {

    SalesEmulatorManagerPage salesEmulatorManagerPage;

    public SalesSteps(Pages pages) {
        super(pages);
    }

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

    @Step
    public void check(String productName, String quantity, String purchasePrice) {
        salesEmulatorManagerPage.check(productName, quantity, purchasePrice);
    }
}
