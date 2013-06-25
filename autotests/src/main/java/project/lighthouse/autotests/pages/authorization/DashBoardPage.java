package project.lighthouse.autotests.pages.authorization;

import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import project.lighthouse.autotests.common.CommonPageObject;

public class DashBoardPage extends CommonPageObject {

    public DashBoardPage(WebDriver driver) {
        super(driver);
    }

    @Override
    public void createElements() {
        //To change body of implemented methods use File | Settings | File Templates.
    }

    public void productSectionButtonClick() {
        buttonClick("products");
    }

    public void catalogSectionButtonClick() {
        buttonClick("catalog");
    }

    public void invoicesSectionButtonClick() {
        buttonClick("invoices");
    }

    public void balanceSectionButtonClick() {
        buttonClick("balance");
    }

    public void writeOffsSectionButtonClick() {
        buttonClick("writeOffs");
    }

    public void userSectionButtonClick() {
        buttonClick("users");
    }

    private String getButtonXpath(String hrefParameter) {
        return String.format("//*[@class='button' and contains(@href, '%s')]", hrefParameter);
    }

    private void buttonClick(String hrefParamater) {
        String buttonXpath = getButtonXpath(hrefParamater);
        findElement(By.xpath(buttonXpath)).click();
    }

    public void isNotPresent(By finBy) {
        waiter.waitUntilIsNotVisible(finBy);
    }
}
