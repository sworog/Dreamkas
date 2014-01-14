package project.lighthouse.autotests.fixtures.sprint_25;

import org.jbehave.core.model.ExamplesTable;
import org.joda.time.DateTime;
import project.lighthouse.autotests.fixtures.AbstractFixture;

import java.util.ArrayList;
import java.util.HashMap;
import java.util.List;
import java.util.Map;

public class Us_57_3_Fixture extends AbstractFixture {

    private static final String SHOP_1 = "25573";
    private static final String SHOP_2 = "255731";
    private static final String PRODUCT_ID_1 = "25573";
    private static final String PRODUCT_ID_2 = "255731";
    private static final String SUBCATEGORY_NAME_1 = "defaultSubCategory-s25u573";
    private static final String SUBCATEGORY_NAME_2 = "defaultSubCategory-s25u5731";
    private static final String CATEGORY_NAME_1 = "defaultCategory-s25u573";
    private static final String CATEGORY_NAME_2 = "defaultCategory-s25u5731";
    private static final String GROUP_NAME_1 = "defaultGroup-s25u573";
    private static final String GROUP_NAME_2 = "defaultGroup-s25u5731";


    private Us_57_4_Fixture us_57_4_fixture = new Us_57_4_Fixture();

    public Us_57_4_Fixture.TodayYesterdayWeekAgoDataAreEqualToEachOtherDataSet getTodayYesterdayWeekAgoDataAreEqualToEachOtherDataSet() {
        return us_57_4_fixture.new TodayYesterdayWeekAgoDataAreEqualToEachOtherDataSet(SHOP_1, PRODUCT_ID_1);
    }

    public Us_57_4_Fixture.TodayIsBiggerThanYesterdayAndWeekAgoDataSet getTodayIsBiggerThanYesterdayAndWeekAgoDataSet() {
        return us_57_4_fixture.new TodayIsBiggerThanYesterdayAndWeekAgoDataSet(SHOP_1, PRODUCT_ID_1);
    }

    public Us_57_4_Fixture.TodayIsSmallerThanYesterdayAndBiggerThanWeekAgoDataSet getTodayIsSmallerThanYesterdayAndBiggerThanWeekAgoDataSet() {
        return us_57_4_fixture.new TodayIsSmallerThanYesterdayAndBiggerThanWeekAgoDataSet(SHOP_1, PRODUCT_ID_1);
    }

    public Us_57_4_Fixture.TodayIsBiggerThanYesterdayAndSmallerThanWeekAgoDataSet getTodayIsBiggerThanYesterdayAndSmallerThanWeekAgoDataSet() {
        return us_57_4_fixture.new TodayIsBiggerThanYesterdayAndSmallerThanWeekAgoDataSet(SHOP_1, PRODUCT_ID_1);
    }

    public Us_57_4_Fixture.TodayIsSmallerThanYesterdayAndWeekAgoDataSet getTodayIsSmallerThanYesterdayAndWeekAgoDataSet() {
        return us_57_4_fixture.new TodayIsSmallerThanYesterdayAndWeekAgoDataSet(SHOP_1, PRODUCT_ID_1);
    }

    public ExamplesTable getEmptyFixtureExampleTable() {
        return generateEmptyFixtureExampleTable(SUBCATEGORY_NAME_1);
    }

    public ExamplesTable getExampleTableForShop1SubCategory1() {
        return getExampleTable1(SUBCATEGORY_NAME_1);
    }

    public ExamplesTable getExampleTableForShop1Group2() {
        return getExampleTable2(GROUP_NAME_2);
    }

    public ExamplesTable getExampleTableForShop1Category2() {
        return getExampleTable2(CATEGORY_NAME_2);
    }

    public ExamplesTable getExampleTableForShop1SubCategory2() {
        return getExampleTable2(SUBCATEGORY_NAME_2);
    }

    public ExamplesTable getExampleTableForShop2Group1() {
        return getExampleTable2(GROUP_NAME_1);
    }

    public ExamplesTable getExampleTableForShop2Category1() {
        return getExampleTable2(CATEGORY_NAME_1);
    }

    public ExamplesTable getExampleTableForShop2SubCategory1() {
        return getExampleTable2(SUBCATEGORY_NAME_1);
    }

    public ExamplesTable getExampleTableForShop2Product2() {
        return getExampleTable1(SUBCATEGORY_NAME_2);
    }

    private ExamplesTable getExampleTable1(String subCategoryName) {
        int hour = new DateTime().getHourOfDay();
        List<Map<String, String>> mapList = new ArrayList<>();
        Map<String, String> shop1DataMap = new HashMap<>();
        shop1DataMap.put("name", subCategoryName);
        shop1DataMap.put("todayValue", getFormattedValue(us_57_4_fixture.getMapPrice2().get(hour)));
        shop1DataMap.put("yesterdayValue", getFormattedValue(us_57_4_fixture.getMapPrice3().get(hour)));
        shop1DataMap.put("weekAgoValue", getFormattedValue(us_57_4_fixture.getMapPrice1().get(hour)));
        mapList.add(shop1DataMap);
        return new ExamplesTable("").withRows(mapList);
    }

    private ExamplesTable getExampleTable2(String subCategoryName) {
        int hour = new DateTime().getHourOfDay();
        List<Map<String, String>> mapList = new ArrayList<>();
        Map<String, String> shop1DataMap = new HashMap<>();
        shop1DataMap.put("name", subCategoryName);
        shop1DataMap.put("todayValue", getFormattedValue(us_57_4_fixture.getMapPrice2().get(hour)));
        shop1DataMap.put("yesterdayValue", getFormattedValue(us_57_4_fixture.getMapPrice1().get(hour)));
        shop1DataMap.put("weekAgoValue", getFormattedValue(us_57_4_fixture.getMapPrice3().get(hour)));
        mapList.add(shop1DataMap);
        return new ExamplesTable("").withRows(mapList);
    }

    private ExamplesTable generateEmptyFixtureExampleTable(String name) {
        List<Map<String, String>> mapList = new ArrayList<>();
        Map<String, String> shop1DataMap = new HashMap<>();
        shop1DataMap.put("name", name);
        shop1DataMap.put("todayValue", "0,00 р.");
        shop1DataMap.put("yesterdayValue", "0,00 р.");
        shop1DataMap.put("weekAgoValue", "0,00 р.");
        mapList.add(shop1DataMap);
        return new ExamplesTable("").withRows(mapList);
    }

    public Us_57_4_Fixture.TodayIsBiggerThanYesterdayAndSmallerThanWeekAgoDataSet getTodayIsBiggerThanYesterdayAndSmallerThanWeekAgoDataSetForShop1Product2() {
        return us_57_4_fixture.new TodayIsBiggerThanYesterdayAndSmallerThanWeekAgoDataSet(SHOP_1, PRODUCT_ID_2);
    }

    public Us_57_4_Fixture.TodayIsSmallerThanYesterdayAndBiggerThanWeekAgoDataSet getTodayIsSmallerThanYesterdayAndBiggerThanWeekAgoDataSetForShop1Product1() {
        return us_57_4_fixture.new TodayIsSmallerThanYesterdayAndBiggerThanWeekAgoDataSet(SHOP_1, PRODUCT_ID_1);
    }

    public Us_57_4_Fixture.TodayIsSmallerThanYesterdayAndBiggerThanWeekAgoDataSet getTodayIsSmallerThanYesterdayAndBiggerThanWeekAgoDataSetForShop2Product2() {
        return us_57_4_fixture.new TodayIsSmallerThanYesterdayAndBiggerThanWeekAgoDataSet(SHOP_2, PRODUCT_ID_2);
    }

    public Us_57_4_Fixture.TodayIsBiggerThanYesterdayAndSmallerThanWeekAgoDataSet getTodayIsBiggerThanYesterdayAndSmallerThanWeekAgoDataSetForShop2Product1() {
        return us_57_4_fixture.new TodayIsBiggerThanYesterdayAndSmallerThanWeekAgoDataSet(SHOP_2, PRODUCT_ID_1);
    }

    @Override
    public String getFixtureFileName() {
        return "nullableString";
    }
}
