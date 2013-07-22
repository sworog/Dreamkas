package project.lighthouse.autotests.pages.commercialManager.store;

import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import project.lighthouse.autotests.UrlHelper;
import project.lighthouse.autotests.common.CommonPageObject;
import project.lighthouse.autotests.objects.Store;

public class StoreCardPage extends CommonPageObject {

    final static String STORE_CARD_URL_PATTERN = "%s/stores/%s";

    public StoreCardPage(WebDriver driver) {
        super(driver);
    }

    @Override
    public void createElements() {

    }

    public void checkStoreCardValue(String fieldName, String value) {
        findModelFieldContaining(Store.NAME, fieldName, value);
    }

    public void checkStoreCardHeader(String header) {
        By by = By.xpath(String.format("//h3[contains(., '%s')]", header));
        findVisibleElement(by);
    }

    public String getStoryUrl(String id) {
        return String.format(STORE_CARD_URL_PATTERN, UrlHelper.getWebFrontUrl(), id);
    }

    public void navigateToStoreCardPage(String id) {
        getDriver().navigate().to(getStoryUrl(id));
    }

    public WebElement editButton() {
        return findVisibleElement(By.xpath("//*[@class='page__controls']/a[contains(., 'Редактировать')]"));
    }

    public WebElement createNewDepartmentButton() {
        return findElement(By.xpath("//a[@class='button' and contains(text(), 'Создать отдел')]"));
    }

    public WebElement findRowByDepartmentNumber(String departmentNumber){
        return findVisibleElement(
                By.xpath(
                        String.format("//td[contains(text(), '%s')]/../tr", departmentNumber)
                )
        );
    }
}
