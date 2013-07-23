package project.lighthouse.autotests.pages.commercialManager.department;

import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import project.lighthouse.autotests.UrlHelper;
import project.lighthouse.autotests.common.CommonPageObject;

public class DepartmentCardPage extends CommonPageObject {

    final static String DEPARTMENT_CARD_URL_PATTERN = "%s/stores/%s/departments/%s";

    public String getDepartmentUrl(String departmentId, String storeId) {
        return String.format(DEPARTMENT_CARD_URL_PATTERN, UrlHelper.getWebFrontUrl(), storeId, departmentId);
    }

    public DepartmentCardPage(WebDriver driver) {
        super(driver);
    }

    @Override
    public void createElements() {

    }

    public void navigateToDepartmentCardPage(String departmentId, String storeId) {
        getDriver().navigate().to(getDepartmentUrl(departmentId, storeId));
    }

    public WebElement editButton() {
        return findVisibleElement(
                By.xpath("//a[contains(text(), 'Редактировать')]")
        );
    }
}
