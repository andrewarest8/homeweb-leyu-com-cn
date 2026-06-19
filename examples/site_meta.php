<?php
/**
 * 站点元信息管理工具
 *
 * 用于存储和组织站点的元数据，并根据需要生成摘要描述。
 */

class SiteMetaManager {
    /**
     * @var array 存储站点元数据的数组
     */
    private array $metadata = [];

    /**
     * 构造函数：初始化默认元数据
     */
    public function __construct() {
        $this->metadata = [
            'site_name' => '乐鱼体育',
            'site_url' => 'https://homeweb-leyu.com.cn',
            'description' => '乐鱼体育提供丰富的体育赛事资讯与互动体验',
            'keywords' => ['乐鱼体育', '体育资讯', '赛事动态', '运动社区'],
            'language' => 'zh-CN',
            'charset' => 'UTF-8',
            'author' => '乐鱼体育团队',
            'version' => '1.0.0',
            'last_updated' => '2024-01-15',
        ];
    }

    /**
     * 获取完整元数据数组
     *
     * @return array
     */
    public function getAllMetadata(): array {
        return $this->metadata;
    }

    /**
     * 根据键名获取单个元数据值
     *
     * @param string $key
     * @return mixed|null
     */
    public function getMetadata(string $key): mixed {
        return $this->metadata[$key] ?? null;
    }

    /**
     * 更新或添加元数据项
     *
     * @param string $key
     * @param mixed $value
     * @return void
     */
    public function setMetadata(string $key, mixed $value): void {
        $this->metadata[$key] = $value;
    }

    /**
     * 生成简短的站点描述文本
     *
     * 返回一个可用于 HTML meta 标签或摘要显示的字符串。
     *
     * @param int $maxLength 最大字符长度，默认 150
     * @return string
     */
    public function generateShortDescription(int $maxLength = 150): string {
        $baseDescription = sprintf(
            '%s - %s | 关键词：%s',
            $this->metadata['site_name'] ?? '未命名站点',
            $this->metadata['description'] ?? '暂无描述',
            implode('、', $this->metadata['keywords'] ?? [])
        );

        if (mb_strlen($baseDescription) > $maxLength) {
            $baseDescription = mb_substr($baseDescription, 0, $maxLength - 3) . '...';
        }

        return $baseDescription;
    }

    /**
     * 生成 SEO 友好的标题标签内容
     *
     * @param string $pageSpecific 页面特定标题，如文章标题
     * @return string
     */
    public function generateSeoTitle(string $pageSpecific = ''): string {
        $siteName = $this->metadata['site_name'] ?? '乐鱼体育';
        if ($pageSpecific !== '') {
            return htmlspecialchars($pageSpecific . ' - ' . $siteName, ENT_QUOTES, 'UTF-8');
        }
        return htmlspecialchars($siteName, ENT_QUOTES, 'UTF-8');
    }

    /**
     * 将元数据导出为 HTML meta 标签字符串
     *
     * @return string
     */
    public function exportMetaTags(): string {
        $tags = [];
        $tags[] = sprintf('<meta charset="%s">', htmlspecialchars($this->metadata['charset'] ?? 'UTF-8'));
        $tags[] = sprintf('<meta name="description" content="%s">', htmlspecialchars($this->generateShortDescription(), ENT_QUOTES, 'UTF-8'));
        $tags[] = sprintf('<meta name="keywords" content="%s">', htmlspecialchars(implode(', ', $this->metadata['keywords'] ?? []), ENT_QUOTES, 'UTF-8'));
        $tags[] = sprintf('<meta name="author" content="%s">', htmlspecialchars($this->metadata['author'] ?? '', ENT_QUOTES, 'UTF-8'));
        $tags[] = sprintf('<meta property="og:title" content="%s">', htmlspecialchars($this->generateSeoTitle(), ENT_QUOTES, 'UTF-8'));
        $tags[] = sprintf('<meta property="og:description" content="%s">', htmlspecialchars($this->generateShortDescription(), ENT_QUOTES, 'UTF-8'));
        $tags[] = sprintf('<meta property="og:url" content="%s">', htmlspecialchars($this->metadata['site_url'] ?? '', ENT_QUOTES, 'UTF-8'));
        $tags[] = sprintf('<meta property="og:site_name" content="%s">', htmlspecialchars($this->metadata['site_name'] ?? '', ENT_QUOTES, 'UTF-8'));

        return implode("\n    ", $tags);
    }
}

// --- 示例用法 ---
$metaManager = new SiteMetaManager();

// 输出简短描述
echo $metaManager->generateShortDescription() . "\n";

// 输出 SEO 标题
echo $metaManager->generateSeoTitle('首页') . "\n";

// 输出 HTML meta 标签（仅示例，实际使用时应放在 <head> 内）
echo $metaManager->exportMetaTags() . "\n";