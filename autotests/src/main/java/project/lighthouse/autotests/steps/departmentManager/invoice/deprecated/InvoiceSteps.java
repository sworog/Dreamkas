package project.lighthouse.autotests.steps.departmentManager.invoice.deprecated;

import junit.framework.Assert;
import net.thucydides.core.annotations.Step;
import net.thucydides.core.steps.ScenarioSteps;
import org.openqa.selenium.By;
import project.lighthouse.autotests.elements.preLoader.CheckBoxPreloader;
import project.lighthouse.autotests.pages.departmentManager.invoice.deprecated.InvoiceBrowsing;

public class InvoiceSteps extends ScenarioSteps {

    InvoiceBrowsing invoiceBrowsing;

    @Step
    public void itemClick(String itemName) {
        invoiceBrowsing.itemClick(itemName);
    }

    @Step
    public void checkTheStateOfCheckBox(String itemName, String state) {
        String checkBoxState = invoiceBrowsing.getItemAttribute(itemName, "checked");
        switch (state) {
            case "checked":
                if (checkBoxState != null) {
                    if (!checkBoxState.equals("true")) {
                        Assert.fail("CheckBox is not checked!");
                    }
                } else {
                    Assert.fail("CheckBox is not checked!");
                }
                break;
            case "unChecked":
                if (checkBoxState != null) {
                    Assert.fail("CheckBox is not unchecked!");
                }
                break;
        }
    }

    @Step
    public void checkTheCheckBoxText(String itemName, String text) {
        String actualText = invoiceBrowsing.getItems().get(itemName).getVisibleWebElement().findElement(By.xpath(".//..")).getText();
        Assert.assertEquals(text, actualText);
    }

    @Step
    public void checkBoxPreLoaderWait() {
        new CheckBoxPreloader(getDriver()).await();
    }
}
