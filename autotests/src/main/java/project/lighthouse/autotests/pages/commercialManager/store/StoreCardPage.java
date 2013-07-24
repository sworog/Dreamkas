package project.lighthouse.autotests.pages.commercialManager.store;

import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import project.lighthouse.autotests.UrlHelper;
import project.lighthouse.autotests.common.CommonPageObject;
import project.lighthouse.autotests.elements.SelectByVisibleText;
import project.lighthouse.autotests.objects.Store;

public class StoreCardPage extends CommonPageObject {

    final static String STORE_CARD_URL_PATTERN = "%s/stores/%s";

    public StoreCardPage(WebDriver driver) {
        super(driver);
    }

    @Override
    public void createElements() {
        items.put("store manager select", new SelectByVisibleText(this, By.id("select_storeManagers")));
    }

    public void checkStoreCardValue(String fieldName, String value) {
        findModelFieldContaining(Store.NAME, fieldName, value);
    }

    public void checkStoreCardHeader(String header) {
        String storeNumber = findVisibleElement(
                By.xpath("//*[@model_attr='number' and @model_name='store']")
        ).getText();
        if (!header.equals(storeNumber)) {
            String errorMessage = String.format("Store number should be '%s', but it's '%s'", header, storeNumber);
            throw new AssertionError(errorMessage);
        }
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

    public WebElement findRowByDepartmentNumber(String departmentNumber) {
        return findVisibleElement(
                By.xpath(
                        String.format("//td[contains(text(), '%s')]/../tr", departmentNumber)
                )
        );
    }

    public WebElement findPromotedStoreManager(String value) {
        return findVisibleElement(
                By.xpath(
                        String.format("//*[not(contains(@class, 'store__managerItem preloader_rows'))]/*[@model_attr='name' and @model_name='user' and text()='%s']", value)
                )
        );
    }

    public void promoteStoreManager(String storeManager) {
        try {
            setStoreManager(storeManager);
        } catch (Exception e) {
            String errorMessage = String.format("Can't promote store manager named '%s', he doesn't exist in drop down list", storeManager);
            throw new AssertionError(errorMessage);
        }
    }

    public void setStoreManager(String storeManager) {
        items.get("store manager select").setValue(storeManager);
    }

    public void promoteNotStoreManager(String notStoreManager) {
        try {
            setStoreManager(notStoreManager);
            String errorMessage = String.format("The user named '%s' can't be promoted to store manager!", notStoreManager);
            throw new AssertionError(errorMessage);
        } catch (Exception e) {
        }
    }

    public WebElement checkPromotedStoreManager(String storeManager) {
        try {
            return findPromotedStoreManager(storeManager);
        } catch (Exception e) {
            String errorMessage = String.format("Store manager '%s' should be promoted and selected!", storeManager);
            throw new AssertionError(errorMessage);
        }
    }

    public void checkPromotedStoreManagerIsNotPresent(String storeManager) {
        try {
            findPromotedStoreManager(storeManager);
            String errorMessage = String.format("Store manager named '%s' should be not promoted!", storeManager);
            throw new AssertionError(errorMessage);
        } catch (Exception e) {
        }
    }

    public void unPromoteStoreManager(String storeManager) {
        checkPromotedStoreManager(storeManager).findElement(By.xpath("../i")).click();
    }
}
