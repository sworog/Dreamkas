package project.lighthouse.autotests.objects.web.abstractObjects.objectInterfaces;

import project.lighthouse.autotests.objects.web.compare.CompareResults;

import java.util.Map;

/**
 * Interface for {@link project.lighthouse.autotests.objects.web.abstractObjects.AbstractObject} to get compare result error
 */
public interface ResultComparable {

    public CompareResults getCompareResults(Map<String, String> row);
}
