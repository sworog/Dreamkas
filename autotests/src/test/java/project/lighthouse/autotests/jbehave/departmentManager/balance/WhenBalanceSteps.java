package project.lighthouse.autotests.jbehave.departmentManager.balance;

import net.thucydides.core.annotations.Steps;
import org.jbehave.core.annotations.Given;
import org.jbehave.core.annotations.When;
import project.lighthouse.autotests.fixtures.sprint_23.Us_50_Fixture;
import project.lighthouse.autotests.steps.ConsoleCommandSteps;
import project.lighthouse.autotests.steps.departmentManager.BalanceSteps;

import javax.xml.parsers.ParserConfigurationException;
import javax.xml.transform.TransformerException;
import javax.xml.xpath.XPathExpressionException;
import java.io.IOException;

public class WhenBalanceSteps {

    @Steps
    BalanceSteps balanceSteps;

    @Steps
    ConsoleCommandSteps consoleCommandSteps;

    Us_50_Fixture fixture = new Us_50_Fixture();

    @When("the user opens product balance tab")
    public void whenTheUserOpensProductBalanceTab() {
        balanceSteps.balanceTabClick();
    }

    @When("the user clicks on the inventory table item by '$sku'")
    public void whenTheUserClicksOnTheProductNameLinkOfTheInventoryTableItemBySku(String sku) {
        balanceSteps.clickPropertyByLocator(sku);
    }

    @Given("sales are created with '$days' days shift")
    public void thenSalesAreCreatedWithDaysShift(int days) throws ParserConfigurationException, IOException, XPathExpressionException, TransformerException, InterruptedException {
        consoleCommandSteps.runCapAutoTestsSymfonyImportSalesLocalCommand(fixture.createPositiveSalesXmlFile(days));
    }

    @Given("sales are created with '$days' days shift and '$productName' product name")
    public void thenSalesAreCreatedWithDaysShiftForNegativeTest(int days, String productName) throws ParserConfigurationException, IOException, XPathExpressionException, TransformerException, InterruptedException {
        consoleCommandSteps.runCapAutoTestsSymfonyImportSalesLocalCommand(fixture.createNegativeSalesXmlFile(days, productName));
    }
}
