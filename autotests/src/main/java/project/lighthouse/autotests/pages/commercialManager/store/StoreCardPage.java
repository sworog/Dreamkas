package project.lighthouse.autotests.pages.commercialManager.store;

import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import project.lighthouse.autotests.UrlHelper;
import project.lighthouse.autotests.common.CommonPageObject;
import project.lighthouse.autotests.elements.SelectByVisibleText;
import project.lighthouse.autotests.objects.api.Store;

import static junit.framework.Assert.assertEquals;
import static junit.framework.Assert.fail;

public class StoreCardPage extends CommonPageObject {

    final static String STORE_CARD_URL_PATTERN = "%s/stores/%s";

    public StoreCardPage(WebDriver driver) {
        super(driver);
    }

    @Override
    public void createElements() {
        items.put("store manager select", new SelectByVisibleText(this, By.id("select_storeManagers")));
        items.put("department manager select", new SelectByVisibleText(this, By.id("select_departmentManagers")));
    }

    public void checkStoreCardValue(String fieldName, String value) {
        findModelFieldContaining(Store.NAME, fieldName, value);
    }

    public void checkStoreCardHeader(String header) {
        String storeNumber = findVisibleElement(
                By.xpath("//*[@model_attr='number' and @model_name='store']")
        ).getText();
        assertEquals(
                String.format("Store number should be '%s', but it's '%s'", header, storeNumber),
                header, storeNumber
        );
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
                        String.format("//*[not(contains(@class, 'store__storeManagerItem preloader_rows'))]/*[@model_attr='name' and @model_name='user' and text()='%s']", value)
                )
        );
    }

    public WebElement findPromotedDepartmentManager(String value) {
        return findVisibleElement(
                By.xpath(
                        String.format("//*[not(contains(@class, 'store__departmentManagerItem preloader_rows'))]/*[@model_attr='name' and @model_name='user' and text()='%s']", value)
                )
        );
    }

    public void promoteStoreManager(String storeManager) {
        try {
            setStoreManager(storeManager);
        } catch (Exception e) {
            fail(
                    String.format("Can't promote store manager named '%s', he doesn't exist in drop down list", storeManager)
            );
        }
    }

    public void setStoreManager(String storeManager) {
        items.get("store manager select").setValue(storeManager);
    }

    public void promoteNotStoreManager(String notStoreManager) {
        try {
            setStoreManager(notStoreManager);
            fail(
                    String.format("The user named '%s' can't be promoted to store manager!", notStoreManager)
            );
        } catch (Exception ignored) {
        }
    }

    public WebElement checkPromotedStoreManager(String storeManager) {
        try {
            return findPromotedStoreManager(storeManager);
        } catch (Exception e) {
            fail(
                    String.format("Store manager '%s' should be promoted and selected!", storeManager)
            );
        }
        return null;
    }

    public void checkPromotedStoreManagerIsNotPresent(String storeManager) {
        try {
            findPromotedStoreManager(storeManager);
            fail(
                    String.format("Store manager named '%s' should be not promoted!", storeManager)
            );
        } catch (Exception ignored) {
        }
    }

    public void unPromoteStoreManager(String storeManager) {
        checkPromotedStoreManager(storeManager).findElement(By.xpath("../i")).click();
    }

    public void setDepartmentManager(String departmentManager) {
        items.get("department manager select").setValue(departmentManager);
    }

    public void promoteDepartmentManager(String departmentManager) {
        try {
            setDepartmentManager(departmentManager);
        } catch (Exception e) {
            fail(
                    String.format("Can't promote department manager named '%s', he doesn't exist in drop down list", departmentManager)
            );
        }
    }

    public void promoteNotDepartmentManager(String notDepartmentManager) {
        try {
            setDepartmentManager(notDepartmentManager);
            fail(
                    String.format("The user named '%s' can't be promoted to store manager!", notDepartmentManager)
            );
        } catch (Exception ignored) {
        }
    }

    public WebElement checkPromotedDepartmentManager(String departmentManager) {
        try {
            return findPromotedDepartmentManager(departmentManager);
        } catch (Exception e) {
            fail(
                    String.format("Store manager '%s' should be promoted and selected!", departmentManager)
            );
        }
        return null;
    }

    public void checkPromotedDepartmentManagerIsNotPresent(String departmentManager) {
        try {
            findPromotedDepartmentManager(departmentManager);
            fail(
                    String.format("Store manager named '%s' should be not promoted!", departmentManager)
            );
        } catch (Exception ignored) {
        }
    }

    public void unPromoteDepartmentManager(String departmentManager) {
        checkPromotedDepartmentManager(departmentManager).findElement(By.xpath("../i")).click();
    }
}
